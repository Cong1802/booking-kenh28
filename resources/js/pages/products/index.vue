<template>
  <div class="mb-50">
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div class="col-lg-12">
        <div>
          <div class="row">
            <div class="col-xl-2 col-md-3 col-4">
              <search
                v-model="query"
                @reset-pagination="resetPagination()"
                @reload="reload"
              />
            </div>
            <div class="col-xl-10 col-md-9 col-8 text-right">
              <div class="btn-group">
                <a
                  href="/products/pdf"
                  v-tooltip="$t('common.export_table')"
                  class="btn btn-secondary"
                >
                  <i class="fas fa-download"></i>
                </a>
                <a
                  @click="print"
                  v-tooltip="$t('common.print_table')"
                  class="btn btn-info"
                >
                  <i class="fas fa-print"></i>
                </a>
                <router-link
                  v-if="$can('product-create')"
                  :to="{ name: 'products.create' }"
                  class="btn btn-primary"
                >
                  {{ $t("common.create") }}
                  <i class="fas fa-plus-circle d-none d-sm-inline-block" />
                </router-link>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0 position-relative">
          <table-loading v-show="loading" />
          <div
            id="printMe"
            v-if="!loading"
            class="table-responsive table-custom mt-3"
          >
            <table class="table">
              <thead>
                <tr>
                  <th>{{ $t("common.s_no") }}</th>
                  <th>{{ $t("common.name") }}</th>
                  <th>{{ $t("common.timePost") }}</th>
                  <th>{{ $t("common.employees") }}</th>
                  <th>{{ $t("common.sub_category") }}</th>
                  <th>{{ $t("common.partner") }}</th>
                  <th>{{ $t("products.list.common.total_price") }}</th>
                  <th>{{ $t("common.status") }}</th>
                  <th
                    v-if="
                      $can('product-edit') ||
                      $can('product-view') ||
                      $can('product-delete')
                    "
                    class="text-right no-print"
                  >
                    {{ $t("common.action") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-show="items.length" v-for="(data, i) in items" :key="i">
                  <td>
                    <span v-if="pagination && pagination.current_page > 1">
                      {{
                        pagination.per_page * (pagination.current_page - 1) +
                        (i + 1)
                      }}
                    </span>
                    <span v-else>{{ i + 1 }}</span>
                  </td>
                  <td>
                    <router-link
                      :to="{
                        name: 'products.show',
                        params: { slug: data.slug },
                      }"
                    >
                      {{ data.name }}
                    </router-link>
                  </td>
                  <td>
                    <span v-if="data.timePost"
                      >{{ data.timePost }}
                    </span>
                  </td>
                  <td>
                    <span v-if="data.employee"
                      >{{ data.employee }}
                    </span>
                  </td>
                  <td>
                    <span v-if="data.subCategory"
                      >{{ data.subCategory.name }}
                    </span>
                  </td>
                  <td>{{ data.itemModel }}</td>
                  <td>
                    <span>{{ data.regularPrice }} </span>
                  </td>
                  <td>
                    <span v-if="data.status === 1" class="badge bg-success">{{
                      $t("common.active")
                    }}</span>
                    <span v-else class="badge bg-danger">{{
                      $t("common.in_active")
                    }}</span>
                  </td>
                  <td
                    v-if="
                      $can('product-edit') ||
                      $can('product-view') ||
                      $can('product-delete')
                    "
                    class="text-right no-print"
                  >
                    <div class="btn-group">
                      <router-link
                        v-if="$can('product-view')"
                        v-tooltip="$t('common.view')"
                        :to="{
                          name: 'products.show',
                          params: { slug: data.slug },
                        }"
                        class="btn btn-primary btn-sm"
                      >
                        <i class="fas fa-eye" />
                      </router-link>
                      <router-link
                        v-if="$can('product-edit')"
                        v-tooltip="$t('common.edit')"
                        :to="{
                          name: 'products.edit',
                          params: { slug: data.slug },
                        }"
                        class="btn btn-info btn-sm"
                      >
                        <i class="fas fa-edit" />
                      </router-link>
                      <a
                        v-if="$can('product-delete')"
                        v-tooltip="$t('common.delete')"
                        href="#"
                        class="btn btn-danger btn-sm"
                        @click="deleteData(data.slug)"
                      >
                        <i class="fas fa-trash" />
                      </a>
                    </div>
                  </td>
                </tr>
                <tr v-show="!loading && !items.length">
                  <td colspan="10">
                    <EmptyTable />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.card-body -->
        <div class="dtable-footer">
          <div class="form-group row display-per-page">
            <label>{{ $t("per_page") }} </label>
            <div>
              <select
                @change="updatePerPager"
                v-model="perPage"
                class="form-control form-control-sm ml-1"
              >
                <option value="10">10</option>
                <option value="1">1</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
          </div>
          <!-- pagination-start -->
          <pagination
            v-if="pagination && pagination.last_page > 1"
            :pagination="pagination"
            :offset="5"
            class="justify-flex-end"
            @paginate="paginate"
          />
          <!-- pagination-end -->
        </div>
      </div>
    </div>
    <Modal v-if="showModal" @close="previewModal()">
      <h5 slot="header">{{ $t("common.modal_header") }}</h5>
      <div class="w-100" slot="body">
        <img :src="imagePath" class="rounded img-fluid" loading="lazy" />
      </div>
    </Modal>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Modal from "../../components/Modal.vue";
export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("products.list.index.page_title") };
  },
  components: {
    Modal,
  },
  data: () => ({
    breadcrumbsCurrent: "products.list.index.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "products.list.index.breadcrumbs_first",
        url: "home",
      },
      {
        name: "products.list.index.breadcrumbs_active",
        url: "",
      },
    ],
    showModal: false,
    perPage: 10,
    query: "",
  }),
  // Map Getters
  computed: {
    ...mapGetters("operations", ["items", "loading", "pagination", "appInfo"]),
  },
  watch: {
    // watch search data
    query: function (newQ, oldQ) {
      if (newQ === "") {
        this.getData();
      } else {
        this.searchData();
      }
    },
  },
  created() {
    this.getData();
    this.prefix = this.appInfo.productPrefix;
    this.catPrefix = this.appInfo.proCatPrefix;
    this.subCatPrefix = this.appInfo.proSubCatPrefix;
  },
  methods: {
    // update per page count
    updatePerPager() {
      this.pagination.current_page = 1;
      this.query === "" ? this.getData() : this.searchData();
    },
    // get data
    async getData() {
      this.$store.state.operations.loading = true;
      let currentPage = this.pagination ? this.pagination.current_page : 1;
      await this.$store.dispatch("operations/fetchData", {
        path: "/api/products?page=",
        currentPage: currentPage + "&perPage=" + this.perPage,
      });
    },

    // Pagination
    async paginate() {
      this.query === "" ? this.getData() : this.searchData();
    },

    // Reset pagination
    async resetPagination() {
      this.pagination.current_page = 1;
    },

    // search data
    async searchData() {
      this.$store.state.operations.loading = true;
      await this.$store.dispatch("operations/searchData", {
        path: "/api/products/search",
        term: this.query,
        currentPage: this.pagination.current_page + "&perPage=" + this.perPage,
      });
    },

    // Reload after search
    async reload() {
      this.query = "";
    },

    // dispaly modal
    previewModal(image) {
      this.imagePath = image;
      if (this.showModal) {
        return (this.showModal = false);
      }
      return (this.showModal = true);
    },

    // print table
    async print() {
      await this.$htmlToPaper("printMe");
    },

    // delete data
    async deleteData(slug) {
      Swal.fire({
        title: this.$t("common.delete_title"),
        text: this.$t("products.list.index.delete_warning"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("common.delete_confirm_text"),
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.$store
            .dispatch("operations/deleteData", {
              path: "/api/products/",
              slug: slug,
            })
            .then((response) => {
              if (response === true) {
                Swal.fire(
                  this.$t("common.deleted"),
                  this.$t("common.delete_success"),
                  "success"
                );
              } else {
                this.$t("common.failed"),
                  this.$t("common.delete_failed"),
                  "warning";
              }
            });
        }
      });
    },
  },
};
</script>
