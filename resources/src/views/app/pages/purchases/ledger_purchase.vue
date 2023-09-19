<template>
    <div class="main-content">
        <breadcumb :page="$t('Ledger Reports')" :folder="$t('Purchases')" />
        <div>
            <b-row>
                <!-- suplier -->
                <b-col lg="3" md="3" sm="6">
                    <b-form-group :label="$t('Supplier')">
                        <v-select
                            v-model="form.supplier"
                            :reduce="(label) => label.value"
                            :placeholder="$t('Choose_Supplier')"
                            :options="
                                suppliers.map((suppliers) => ({
                                    label: suppliers.name,
                                    value: suppliers.id,
                                }))
                            "
                        ></v-select>
                    </b-form-group>
                </b-col>
                <!-- start date -->
                <b-col lg="3" md="3" sm="6">
                    <b-form-group :label="$t('Start Date')">
                        <b-form-input
                            label="Start Date"
                            aria-describedby="date-feedback"
                            v-model="form.start"
                            type="date"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <!-- end date date -->
                <b-col lg="3" md="3" sm="6">
                    <b-form-group :label="$t('End Date')">
                        <b-form-input
                            label="End Date"
                            aria-describedby="date-feedback"
                            v-model="form.end"
                            type="date"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col lg="3" md="3" sm="6">
                    <b-button
                        @click="Get_Ledgers()"
                        variant="primary mt-4"
                        size="sm"
                        block
                    >
                        {{ $t("Filter") }}
                    </b-button>
                </b-col>
            </b-row>
            <hr />
        </div>
        <div
            v-if="isLoading"
            class="loading_page spinner spinner-primary mr-3"
        ></div>
        <div v-else>
            <vue-good-table
                mode="remote"
                :columns="columns"
                :totalRows="totalRows"
                :rows="ledgers"
                :search-options="{
                    placeholder: $t('Search_this_table'),
                    enabled: true,
                }"
                :select-options="{
                    enabled: true,
                    clearSelectionText: '',
                }"
                :pagination-options="{
                    enabled: true,
                    mode: 'records',
                    nextLabel: 'next',
                    prevLabel: 'prev',
                }"
                :styleClass="
                    showDropdown
                        ? 'tableOne table-hover vgt-table full-height'
                        : 'tableOne table-hover vgt-table non-height'
                "
            >
                <div slot="table-actions" class="mt-2 mb-3">
                    <b-button size="sm" variant="outline-success ripple m-1">
                        <i class="i-File-Copy"></i> PDF
                    </b-button>
                </div>
            </vue-good-table>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import NProgress from "nprogress";
import jsPDF from "jspdf";
import "jspdf-autotable";
export default {
    metaInfo: {
        title: "Ledger Reports",
    },
    data() {
        return {
            isLoading: false,
            serverParams: {
                sort: {
                    field: "id",
                    type: "desc",
                },
                page: 1,
                perPage: 10,
            },
            form: {
                start: new Date().toISOString().slice(0, 10),
                end: new Date().toISOString().slice(0, 10),
                supplier: null,
            },
            selectedIds: [],
            search: "",
            totalRows: "",
            showDropdown: false,
            EditPaiementMode: false,
            Filter_Supplier: "",
            Filter_status: "",
            Filter_Payment: "",
            Filter_warehouse: "",
            Filter_Ref: "",
            Filter_date: "",
            Purchase_id: "",
            suppliers: [],
            ledgers: [],
        };
    },
    mounted() {
        this.$root.$on("bv::dropdown::show", (bvEvent) => {
            this.showDropdown = true;
        });
        this.$root.$on("bv::dropdown::hide", (bvEvent) => {
            this.showDropdown = false;
        });
    },
    computed: {
        ...mapGetters(["currentUserPermissions", "currentUser"]),
        columns() {
            return [
                {
                    label: this.$t("date"),
                    field: "date",
                    tdClass: "text-left",
                    thClass: "text-left",
                },
                {
                    label: this.$t("Invoice"),
                    field: "invoice",
                    tdClass: "text-left",
                    thClass: "text-left",
                },
                {
                    label: this.$t("Manual Invoice"),
                    field: "manual",
                    tdClass: "text-left",
                    thClass: "text-left",
                },
                {
                    label: this.$t("Debit"),
                    field: "debit",
                    tdClass: "text-left",
                    thClass: "text-left",
                },
                {
                    label: this.$t("Credit"),
                    field: "credit",
                    tdClass: "text-left",
                    thClass: "text-left",
                },
                {
                    label: this.$t("Tax"),
                    field: "tax",
                    tdClass: "text-left",
                    thClass: "text-left",
                },
                {
                    label: this.$t("Discount"),
                    field: "discount",
                    tdClass: "text-left",
                    thClass: "text-left",
                },
                {
                    label: this.$t("Balance"),
                    field: "balance",
                    tdClass: "text-left",
                    thClass: "text-left",
                },
            ];
        },
    },
    methods: {
        //------ Toast
        makeToast(variant, msg, title) {
            this.$root.$bvToast.toast(msg, {
                title: title,
                variant: variant,
                solid: true,
            });
        },
        // getting ledgers
        Get_Ledgers() {
            this.isLoading = true;
            if (this.form.supplier === null) {
                this.makeToast(
                    "danger",
                    this.$t("Please choose supplier"),
                    this.$t("Error")
                );
            } else {
                axios
                    .post("purchases/ledgers", this.form)
                    .then((response) => {
                        console.log(response.data);
                        this.ledgers = response.data.ledgers;
                        this.totalRows = response.data.totalRows;
                        this.isLoading = false;
                    })
                    .catch((response) => {
                        setTimeout(() => {
                            this.isLoading = false;
                        }, 500);
                    });
            }
        },
        //------------Get Elements Purchase --------\\
        GetElements() {
            axios
                .get("purchases/create")
                .then((response) => {
                    this.suppliers = response.data.suppliers;
                    this.isLoading = false;
                })
                .catch((response) => {
                    setTimeout(() => {
                        this.isLoading = false;
                    }, 500);
                });
        },
    },
    //-----------------------------  Created function-------------------
    created() {
        this.GetElements();
    },
};
</script>

<style></style>
