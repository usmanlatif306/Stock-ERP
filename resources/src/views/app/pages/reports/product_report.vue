<template>
    <div class="main-content">
        <breadcumb :page="$t('Product Reports')" :folder="$t('Reports')" />
        <div
            v-if="isLoading"
            class="loading_page spinner spinner-primary mr-3"
        ></div>
        <b-row>
            <b-col lg="12" md="12" sm="12">
                <b-card>
                    <b-row>
                        <!-- warehouse -->
                        <b-col lg="6" md="6" sm="12" class="mb-3">
                            <validation-provider
                                name="warehouse"
                                :rules="{ required: true }"
                            >
                                <b-form-group
                                    slot-scope="{ valid, errors }"
                                    :label="$t('warehouse')"
                                >
                                    <v-select
                                        :class="{
                                            'is-invalid': !!errors.length,
                                        }"
                                        :state="
                                            errors[0]
                                                ? false
                                                : valid
                                                ? true
                                                : null
                                        "
                                        @input="Selected_Warehouse"
                                        v-model="warehouse_id"
                                        :reduce="(label) => label.value"
                                        :placeholder="$t('Choose_Warehouse')"
                                        :options="
                                            warehouses.map((warehouses) => ({
                                                label: warehouses.name,
                                                value: warehouses.id,
                                            }))
                                        "
                                    />
                                    <b-form-invalid-feedback>{{
                                        errors[0]
                                    }}</b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <!-- products -->
                        <b-col lg="6" md="6" sm="12" class="mb-3">
                            <validation-provider
                                name="products"
                                :rules="{ required: true }"
                            >
                                <b-form-group
                                    slot-scope="{ valid, errors }"
                                    :label="$t('Products')"
                                >
                                    <v-select
                                        :class="{
                                            'is-invalid': !!errors.length,
                                        }"
                                        :state="
                                            errors[0]
                                                ? false
                                                : valid
                                                ? true
                                                : null
                                        "
                                        @input="Product_Information"
                                        v-model="product_id"
                                        :reduce="(label) => label.value"
                                        :placeholder="$t('Choose Product')"
                                        :options="
                                            products.map((product) => ({
                                                label: product.label,
                                                value: product.value,
                                            }))
                                        "
                                    />
                                    <b-form-invalid-feedback>{{
                                        errors[0]
                                    }}</b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                    </b-row>
                    <hr />
                    <!-- Purchases Table -->
                    <div class="mb-5">
                        <h3>Purchases</h3>
                        <vue-good-table
                            mode="remote"
                            :columns="columns_purchases"
                            :totalRows="totalRows_purchases"
                            :rows="purchases"
                            styleClass="vgt-table mt-2"
                        >
                            <template slot="table-row" slot-scope="props">
                                <div v-if="props.column.field == 'status'">
                                    <span
                                        v-if="props.row.status == 'received'"
                                        class="badge badge-outline-success"
                                        >{{ $t("complete") }}</span
                                    >
                                    <span
                                        v-else-if="
                                            props.row.status == 'pending'
                                        "
                                        class="badge badge-outline-info"
                                        >{{ $t("Pending") }}</span
                                    >
                                    <span
                                        v-else
                                        class="badge badge-outline-warning"
                                        >{{ $t("Ordered") }}</span
                                    >
                                </div>
                                <div
                                    v-else-if="
                                        props.column.field == 'payment_status'
                                    "
                                >
                                    <span
                                        v-if="
                                            props.row.payment_status == 'paid'
                                        "
                                        class="badge badge-outline-success"
                                        >{{ $t("Paid") }}</span
                                    >
                                    <span
                                        v-else-if="
                                            props.row.payment_status ==
                                            'partial'
                                        "
                                        class="badge badge-outline-primary"
                                        >{{ $t("partial") }}</span
                                    >
                                    <span
                                        v-else
                                        class="badge badge-outline-warning"
                                        >{{ $t("Unpaid") }}</span
                                    >
                                </div>
                            </template>
                        </vue-good-table>
                    </div>
                    <!-- Sales Table -->
                    <div class="mb-5">
                        <h3>Sales</h3>
                        <vue-good-table
                            mode="remote"
                            :columns="columns_sales"
                            :totalRows="totalRows_sales"
                            :rows="sales"
                            styleClass="vgt-table mt-2"
                        >
                            <template slot="table-row" slot-scope="props">
                                <div v-if="props.column.field == 'status'">
                                    <span
                                        v-if="props.row.status == 'completed'"
                                        class="badge badge-outline-success"
                                        >{{ $t("complete") }}</span
                                    >
                                    <span
                                        v-else-if="
                                            props.row.status == 'pending'
                                        "
                                        class="badge badge-outline-info"
                                        >{{ $t("Pending") }}</span
                                    >
                                    <span
                                        v-else
                                        class="badge badge-outline-warning"
                                        >{{ $t("Ordered") }}</span
                                    >
                                </div>
                                <div
                                    v-else-if="
                                        props.column.field == 'payment_status'
                                    "
                                >
                                    <span
                                        v-if="
                                            props.row.payment_status == 'paid'
                                        "
                                        class="badge badge-outline-success"
                                        >{{ $t("Paid") }}</span
                                    >
                                    <span
                                        v-else-if="
                                            props.row.payment_status ==
                                            'partial'
                                        "
                                        class="badge badge-outline-primary"
                                        >{{ $t("partial") }}</span
                                    >
                                    <span
                                        v-else
                                        class="badge badge-outline-warning"
                                        >{{ $t("Unpaid") }}</span
                                    >
                                </div>
                            </template>
                        </vue-good-table>
                    </div>
                    <!-- Returns Sale Table -->
                    <div class="mb-5">
                        <h3>Sale Returns</h3>
                        <vue-good-table
                            mode="remote"
                            :columns="columns_returns_sale"
                            :totalRows="totalRows_Return_sale"
                            :rows="returns_sale"
                            styleClass="vgt-table mt-2"
                        >
                            <template slot="table-row" slot-scope="props">
                                <div v-if="props.column.field == 'status'">
                                    <span
                                        v-if="props.row.status == 'received'"
                                        class="badge badge-outline-success"
                                        >{{ $t("Received") }}</span
                                    >
                                    <span
                                        v-else
                                        class="badge badge-outline-info"
                                        >{{ $t("Pending") }}</span
                                    >
                                </div>

                                <div
                                    v-else-if="
                                        props.column.field == 'payment_status'
                                    "
                                >
                                    <span
                                        v-if="
                                            props.row.payment_status == 'paid'
                                        "
                                        class="badge badge-outline-success"
                                        >{{ $t("Paid") }}</span
                                    >
                                    <span
                                        v-else-if="
                                            props.row.payment_status ==
                                            'partial'
                                        "
                                        class="badge badge-outline-primary"
                                        >{{ $t("partial") }}</span
                                    >
                                    <span
                                        v-else
                                        class="badge badge-outline-warning"
                                        >{{ $t("Unpaid") }}</span
                                    >
                                </div>
                            </template>
                        </vue-good-table>
                    </div>
                    <!-- Adjustments Table -->
                    <div class="mb-5">
                        <h3>Adjustments</h3>
                        <vue-good-table
                            mode="remote"
                            :columns="columns_adjustments"
                            :totalRows="totalRows_adjustments"
                            :rows="adjustments"
                            styleClass="vgt-table mt-2"
                        >
                            <template slot="table-row" slot-scope="props">
                                <div v-if="props.column.field == 'status'">
                                    <span
                                        v-if="props.row.status == 'addition'"
                                        class="badge badge-outline-success"
                                        >{{ $t("Addition") }}</span
                                    >
                                    <span
                                        v-else-if="
                                            props.row.status == 'substraction'
                                        "
                                        class="badge badge-outline-info"
                                        >{{ $t("Substraction") }}</span
                                    >
                                    <span
                                        v-else
                                        class="badge badge-outline-warning"
                                        >{{ $t("Ordered") }}</span
                                    >
                                </div>
                            </template>
                        </vue-good-table>
                    </div>
                    <!-- Transfers Table -->
                    <div class="mb-5">
                        <h3>Transfers</h3>
                        <vue-good-table
                            mode="remote"
                            :columns="columns_transfers"
                            :totalRows="totalRows_transfers"
                            :rows="transfers"
                            styleClass="vgt-table mt-2"
                        >
                            <template slot="table-row" slot-scope="props">
                                <div v-if="props.column.field == 'status'">
                                    <span
                                        v-if="props.row.status == 'completed'"
                                        class="badge badge-outline-success"
                                        >{{ $t("Completed") }}</span
                                    >
                                    <span
                                        v-else-if="props.row.status == 'send'"
                                        class="badge badge-outline-info"
                                        >{{ $t("Send") }}</span
                                    >
                                    <span
                                        v-else
                                        class="badge badge-outline-warning"
                                        >{{ $t("Pending") }}</span
                                    >
                                </div>
                            </template>
                        </vue-good-table>
                    </div>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import NProgress from "nprogress";
export default {
    metaInfo: {
        title: "Product Reports",
    },
    data() {
        return {
            isLoading: false,
            warehouse_id: null,
            product_id: null,
            totalRows_purchases: "",
            totalRows_sales: "",
            totalRows_Return_sale: "",
            totalRows_adjustments: "",
            totalRows_transfers: "",
            warehouses: [],
            products: [],
            purchases: [],
            sales: [],
            adjustments: [],
            transfers: [],
            returns_sale: [],
        };
    },
    computed: {
        ...mapGetters(["currentUser"]),
        columns_purchases() {
            return [
                {
                    label: this.$t("Name"),
                    field: "name",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Variant"),
                    field: "varient",
                    html: true,
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Supplier"),
                    field: "supplier",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Status"),
                    field: "status",
                    html: true,
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Expiry"),
                    field: "expiry",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Price"),
                    field: "price",
                    type: "decimal",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Quantity"),
                    field: "quantity",
                    type: "decimal",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Total"),
                    field: "total",
                    type: "decimal",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("PaymentStatus"),
                    field: "payment_status",
                    html: true,
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
            ];
        },
        columns_sales() {
            return [
                {
                    label: this.$t("Reference"),
                    field: "Ref",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Customer"),
                    field: "client_name",
                    tdClass: "text-left",
                    thClass: "text-left",
                },
                {
                    label: this.$t("Status"),
                    field: "status",
                    html: true,
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Total"),
                    field: "GrandTotal",
                    type: "decimal",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Paid"),
                    field: "paid_amount",
                    type: "decimal",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Due"),
                    field: "due",
                    type: "decimal",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("PaymentStatus"),
                    field: "payment_status",
                    html: true,
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
            ];
        },
        columns_returns_sale() {
            return [
                {
                    label: this.$t("Reference"),
                    field: "Ref",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Customer"),
                    field: "client_name",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Status"),
                    field: "status",
                    html: true,
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Total"),
                    field: "GrandTotal",
                    type: "decimal",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Paid"),
                    field: "paid_amount",
                    type: "decimal",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Due"),
                    field: "due",
                    type: "decimal",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("PaymentStatus"),
                    field: "payment_status",
                    html: true,
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
            ];
        },
        columns_adjustments() {
            return [
                {
                    label: this.$t("Date"),
                    field: "date",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Reference"),
                    field: "Ref",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Warehouse"),
                    field: "warehouse",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Product"),
                    field: "product",
                    html: true,
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Quantity"),
                    field: "quantity",
                    type: "decimal",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Status"),
                    field: "status",
                    html: true,
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
            ];
        },
        columns_transfers() {
            return [
                {
                    label: this.$t("Date"),
                    field: "date",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Reference"),
                    field: "Ref",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("From"),
                    field: "from",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("To"),
                    field: "to",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Product"),
                    field: "product",
                    html: true,
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Quantity"),
                    field: "quantity",
                    type: "decimal",
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Total"),
                    field: "total",
                    html: true,
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
                {
                    label: this.$t("Status"),
                    field: "status",
                    html: true,
                    tdClass: "text-left",
                    thClass: "text-left",
                    sortable: false,
                },
            ];
        },
    },
    methods: {
        //------------- Event Select Warehouse ----------------\\
        Selected_Warehouse(value) {
            this.search_input = "";
            this.product_filter = [];
            this.Get_Products_By_Warehouse(value);
        },

        //----------------- Get Products By Warehouse ------\\
        Get_Products_By_Warehouse(id) {
            this.products = [];
            this.purchases = [];
            this.sales = [];
            this.returns_sale = [];
            this.adjustments = [];
            this.transfers = [];
            // Start the progress bar.
            NProgress.start();
            NProgress.set(0.1);
            axios
                .get("Products/Warehouse/NoVarient/" + id + "?stock=" + 1)
                .then((response) => {
                    // console.log(response.data);
                    this.products = response.data;
                    // console.log(arr);
                    NProgress.done();
                })
                .catch((error) => {});
        },
        //----------------- Get Products Information By Product------\\
        Product_Information(id) {
            this.purchases = [];
            this.sales = [];
            this.returns_sale = [];
            this.adjustments = [];
            this.transfers = [];

            NProgress.start();
            NProgress.set(0.1);
            axios
                // .get("report/product/" + this.warehouse_id + "/" + id)
                .get("report/product/" + id)
                .then((response) => {
                    // console.log(response.data);
                    this.totalRows_purchases = response.data.purchases_rows;
                    this.purchases = response.data.purchases;
                    this.totalRows_sales = response.data.sales_rows;
                    this.sales = response.data.sales;
                    this.totalRows_Return_sale =
                        response.data.sale_returns_rows;
                    this.returns_sale = response.data.sale_returns;
                    this.totalRows_Return_sale =
                        response.data.sale_returns_rows;
                    this.returns_sale = response.data.sale_returns;
                    this.totalRows_adjustments = response.data.adjustments_rows;
                    this.adjustments = response.data.adjustments;
                    this.totalRows_transfers = response.data.transfers_rows;
                    this.transfers = response.data.transfers;

                    NProgress.done();
                })
                .catch((error) => {});
        },

        //----------Get Elements --------\\
        GetElements() {
            axios
                .get("Get_Warehouses/All")
                .then((response) => {
                    this.warehouses = response.data;
                })
                .catch((response) => {
                    setTimeout(() => {
                        this.isLoading = false;
                    }, 500);
                });
        },
    },
    //----------- Created function ---------
    created() {
        this.GetElements();
    },
};
</script>

<style></style>
