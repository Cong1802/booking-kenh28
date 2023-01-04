<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title font-weight-bold">
              <i class="nav-icon fas fa-warehouse"></i>&nbsp;
              {{ $t('products.list.create.form_title') }}
            </h4>
            <router-link
              :to="{ name: 'products.index' }"
              class="btn btn-dark float-right"
            >
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form
            role="form"
            @submit.prevent="saveProduct"
            @keydown="form.onKeydown($event)"
          >
            <div class="card-body">
              <div class="row">
                <div class="form-group col-md-12 col-xl-12">
                  <label for="itemName"
                    >{{ $t('common.item_name') }}
                    <span class="required">*</span></label
                  >
                  <input
                    id="itemName"
                    v-model="form.itemName"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('itemName') }"
                    name="itemName"
                    :placeholder="$t('common.item_name_placeholder')"
                  />
                  <has-error :form="form" field="itemName" />
                </div>

                <div class="combo_contract form-group mx-0 p-0 row col-12">
                  <div class="form-group col-md-6 col-xl-3">
                    <label for="category"
                      >{{ $t('products.list.common.category') }}
                      <span class="required">*</span></label
                    >
                      <v-select
                      label="name"
                      v-model="form.category"
                      :options="categories"
                      :class="{ 'is-invalid': form.errors.has('category') }"
                      name="category"
                      :placeholder="$t('common.category_name_placeholder')"
                      @input="RenderSubCategories"
                    />
                    <has-error :form="form" field="category" />
                  </div>
                  <div class="form-group col-md-6 col-xl-3">
                    <label for="subCategory"
                      >{{ $t('common.sub_category') }}
                      <span class="required">*</span></label
                    >
                    <v-select
                      v-model="form.subCategory"
                      :options="subCategories"
                      label="name"
                      :class="{ 'is-invalid': form.errors.has('subCategory') }"
                      name="subCategory"
                      :placeholder="$t('common.category_name_placeholder')"
                    />
                    <has-error :form="form" field="subCategory" />
                  </div>
                  <div class="form-group col-md-6 col-xl-3">
                    <label for="timePost"
                      >{{ $t('products.list.common.time_post') }}
                      <span class="required">*</span></label
                    >
                    <input
                      id="timePost"
                      type="time"
                      name="timePost"
                      v-model="form.timePost"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('timePost') }"
                    />
                    <has-error :form="form" field="timePost" />
                  </div>
                  <div class="form-group col-md-6 col-xl-3">
                    <label for="post_number"
                      >{{ $t('common.post_number') }}
                      <span class="required">*</span></label
                    >
                    <input
                      id="post_number"
                      type="number"
                      min="0"
                      name="post_number"
                      v-model="form.post_number"
                      class="form-control"
                      :placeholder="$t('common.post_number_placeholder')"
                      :class="{ 'is-invalid': form.errors.has('post_number') }"
                    />
                    <has-error :form="form" field="post_number" />
                  </div>
                </div>

                <div v-if="cb_contract" class="d-flex justify-content-end col-12">
                  <button @click="add_CBcontract" type="button" class="btn btn-primary">Thêm bài viết</button>
                </div>

                <div class="form-group col-md-6 col-xl-4">
                  <label for="regularPrice"
                    >{{ $t('products.list.common.regular_price') }}
                    <span class="required">*</span></label
                  >
                  <input
                    id="regularPrice"
                    v-model="form.regularPrice"
                    type="number"
                    step="any"
                    min="0"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('regularPrice') }"
                    name="regularPrice"
                    :placeholder="
                      $t('products.list.common.regular_price_placeholder')
                    "
                  />
                  <has-error :form="form" field="regularPrice" />
                </div>
                <div class="form-group col-md-6 col-xl-4">
                  <div class="input-group">
                    <label for="ads" class="col-md-12">{{
                      $t('common.ads')
                    }}</label>
                    <input
                      v-model="form.ads"
                      type="number"
                      min="0"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('discount') }"
                      name="ads"
                      :placeholder="$t('common.ads_placeholder')"
                      aria-label="ads"
                      aria-describedby="basic-addon1"
                    />
                    <has-error :form="form" field="ads" />
                  </div>
                </div>
                <div class="form-group col-md-6 col-xl-4">
                  <label for="total_price">{{
                    $t('products.list.common.total_price')
                  }}<span class="required">*</span></label>
                  <input
                    id="total_price"
                    v-model="form.total_price"
                    type="number"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('total_price') }"
                    name="total_price"
                    :placeholder="
                      $t('products.list.common.total_price_placeholder')
                    "
                  />
                  <has-error :form="form" field="total_price" />
                </div>
                <div class="form-group col-md-6 col-xl-4">
                  <label for="image">{{ $t('common.contract_file') }}</label>
                  <div class="custom-file">
                    <input
                      id="image"
                      type="file"
                      class="custom-file-input"
                      name="image"
                      :class="{ 'is-invalid': form.errors.has('image') }"
                      @change="onFileChange"
                    />
                    <label class="custom-file-label" for="image">{{
                      $t('common.choose_file')
                    }}</label>
                  </div>
                  <has-error :form="form" field="image" />
                  <div class="bg-light mt-4 w-25">
                    <img
                      v-if="url"
                      :src="url"
                      class="img-fluid"
                      :alt="$t('common.image_alt')"
                    />
                  </div>
                </div>
                <div class="form-group col-md-6 col-xl-4">
                  <label for="contract_type">{{ $t('common.contract_type') }}</label>
                  <select
                    @change="SelectContractType($event)"
                    id="contract_type"
                    v-model="form.contract_type"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('contract_type') }"
                  >
                    <option value="1">
                      {{ $t('common.only_contract') }}
                    </option>
                    <option value="0">
                      {{ $t('common.combo_contract') }}
                    </option>
                  </select>
                  <has-error :form="form" field="contract_type" />
                </div>
                <div class="form-group col-md-12">
                  <label for="price_difference">{{
                    $t('products.list.common.note_price_difference')
                  }}</label>
                  <textarea
                    id="price_difference"
                    v-model="form.price_difference"
                    type="number"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('sellingPrice') }"
                    name="price_difference"
                    :placeholder="
                      $t('products.list.common.price_difference_placeholder')
                    "
                  >
                  </textarea>
                  <has-error :form="form" field="sellingPrice" />
                </div>
                <div class="form-group col-md-12">
                  <label for="note">{{ $t('common.note') }}</label>
                  <textarea
                    id="note"
                    v-model="form.note"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('note') }"
                    name="note"
                    :placeholder="$t('common.note_placeholder')"
                  ></textarea>
                  <has-error :form="form" field="note" />
                </div>
              </div>
            <div class="card-header">
              <h4 class="card-title font-weight-bold">
                <i class="nav-icon fas fa-users"></i>&nbsp;
                {{ $t('products.list.create.form_title2') }}
              </h4>
            </div>
            <div class="row mt-4">
              <div class="form-group partner col-md-6 col-xl-4">
                <label for="partner">{{ $t('common.partner') }}<span class="required">*</span></label>
                <div class="d-flex justify-content-between">
                <v-select
                  style="width:calc(100% - 100px)"
                  v-model="form.partner"
                  :options="partner"
                  label="name"
                  :class="{ 'is-invalid': form.errors.has('partner') }"
                  name="partner"
                  :placeholder="$t('common.partner_placeholder')"
                />
                <button type="button" data-toggle="modal" @click="showModal" data-target="#exampleModal" data-whatever="@mdo" class="btn btn-primary">Thêm KH</button>
                </div>
                <has-error :form="form" field="partner" />
              </div>
              <div class="form-group col-md-6 col-xl-4">
                <label for="code_contract">{{
                  $t('products.list.common.code_contract')
                }}</label>
                <input
                  id="code_contract"
                  v-model="form.code_contract"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('code_contract') }"
                  name="code_contract"
                  :placeholder="
                    $t('products.list.common.code_contract_placeholder')
                  "
                />
                <has-error :form="form" field="sellingPrice" />
              </div>
              <div class="form-group col-md-6 col-xl-4">
                  <label for="vat_contract">{{
                    $t('products.list.common.vat_contract')
                  }}</label>
                  <input
                    id="vat_contract"
                    v-model="form.vat_contract"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('contract_tax') }"
                    name="vat_contract"
                    :placeholder="
                      $t('products.list.common.vat_contract_placeholder')
                    "
                  />
                  <has-error :form="form" field="contract_tax" />
              </div>
            </div>

            <div class="card-header">
              <h4 class="card-title font-weight-bold">
                <i class="nav-icon fas fa-users-cog"></i>&nbsp;
                {{ $t('products.list.create.form_title3') }}
              </h4>
            </div>
            <div class="row my-4">
                <div class="form-group col-md-6 col-xl-4">
                  <label for="team"
                    >{{ $t('common.team') }}
                    <span class="required">*</span></label
                  >
                  <v-select
                    v-model="form.team"
                    :options="team"
                    label="name"
                    :class="{ 'is-invalid': form.errors.has('team') }"
                    name="team"
                    :placeholder="$t('common.team_placeholder')"
                  />
                  <has-error :form="form" field="team" />
                </div>
                <div class="form-group col-md-6 col-xl-4">
                  <label for="employees"
                    >{{ $t('products.list.common.staff') }}
                    <span class="required">*</span></label
                  >
                  <v-select
                    v-model="form.employees"
                    :options="employees"
                    label="name"
                    :class="{ 'is-invalid': form.errors.has('employees') }"
                    name="employees"
                    :placeholder="$t('products.list.common.employees_placeholder')"
                  />
                  <has-error :form="form" field="employees" />
                </div>
            </div>
            </div>
            <div class="card-footer">
              <v-button :loading="form.busy" class="btn btn-primary">
                <i class="fas fa-save" /> {{ $t('common.save') }}
              </v-button>
              <button
                type="reset"
                class="btn btn-secondary float-right"
                @click="form.reset()"
              >
                <i class="fas fa-power-off" /> {{ $t('common.reset') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- modal KH -->
    <div class="modal" v-bind:class="{ show: isActive}" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $t('clients.create.form_title') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
        <div class="card">
          <!-- form start -->
          <form
            ref="anyName"
            role="form"
            @submit.prevent="saveClient"
            @keydown="form.onKeydown($event)"
          >
            <div class="card-body p-0">
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="name"
                    >{{ $t('common.name') }}
                    <span class="required">*</span></label
                  >
                  <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('name') }"
                    name="name"
                    :placeholder="$t('common.name_placeholder')"
                  />
                  <has-error :form="form" field="name" />
                </div>
                <div class="form-group col-md-12">
                  <label for="email">{{ $t('common.email') }}</label>
                  <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('email') }"
                    name="email"
                    :placeholder="$t('common.email_placeholder')"
                  />
                  <has-error :form="form" field="email" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="phoneNumber"
                    >{{ $t('common.contact_number') }}
                    <span class="required">*</span></label
                  >
                  <input
                    id="phoneNumber"
                    v-model="form.phoneNumber"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('phoneNumber') }"
                    name="phoneNumber"
                    :placeholder="$t('common.contact_number_placeholder')"
                  />
                  <has-error :form="form" field="phoneNumber" />
                </div>
                <div class="form-group col-md-12">
                  <label for="companyName">{{
                    $t('common.company_name')
                  }}</label>
                  <input
                    id="companyName"
                    v-model="form.companyName"
                    type="companyName"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('companyName') }"
                    name="companyName"
                    :placeholder="$t('common.company_name_placeholder')"
                  />
                  <has-error :form="form" field="companyName" />
                </div>
              </div>
              <div class="form-group">
                <label for="address">{{ $t('common.address') }}</label>
                <textarea
                  id="address"
                  v-model="form.address"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('address') }"
                  :placeholder="$t('common.address_placeholder')"
                />
                <has-error :form="form" field="address" />
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="image">{{ $t('common.avt') }}</label>
                  <div class="custom-file">
                    <input
                      id="image"
                      type="file"
                      class="custom-file-input"
                      name="image"
                      :class="{ 'is-invalid': form.errors.has('image') }"
                      @change="onFileChange"
                    />
                    <label class="custom-file-label" for="image">{{
                      $t('common.choose_file')
                    }}</label>
                  </div>
                  <has-error :form="form" field="image" />
                  <div class="bg-light w-25">
                    <img
                      v-if="url"
                      :src="url"
                      class="img-fluid"
                      :alt="$t('common.image_alt')"
                    />
                  </div>
                </div>
                <div class="form-group col-md-12">
                  <label for="status">{{ $t('common.status') }}</label>
                  <select
                    id="status"
                    v-model="form.status"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('status') }"
                  >
                    <option value="1">{{ $t('common.active') }}</option>
                    <option value="0">{{ $t('common.in_active') }}</option>
                  </select>
                  <has-error :form="form" field="status" />
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="modal-footer">
              <v-button :loading="form.busy" class="btn btn-primary">
                  <i class="fas fa-save" /> {{ $t('common.save') }}
              </v-button>
              <button
                  type="reset"
                  class="btn btn-secondary float-right"
                  @click="formClient.reset()"
                >
                  <i class="fas fa-power-off" /> {{ $t('common.reset') }}
              </button>
            </div>
          </form>
          </div>
        </div>

          </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Form from 'vform'
import Button from '../../components/Button.vue'
export default {
  components: { Button },
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return { title: this.$t('products.list.create.page_title') }
  },
  data: () => ({
    html_contract: '',
    cb_contract: false,
    isActive: true,
    timezone: new Date().toString().match(/([A-Z]+[\+-][0-9]+.*)/)[1],
    breadcrumbsCurrent: 'products.list.create.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'products.list.create.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'products.list.create.breadcrumbs_second',
        url: 'products.index',
      },
      {
        name: 'products.list.create.breadcrumbs_active',
        url: '',
      },
    ],
    formClient: new Form({
      name: '',
      email: '',
      phoneNumber: '',
      companyName: '',
      address: '',
      image: '',
      status: 1,
    }),
    form: new Form({
      itemName: '',
      subCategory: '',
      category: '',
      timePost:'',
      regularPrice:'',
      ads: '',
      total_price: '',
      image: '',
      price_difference:'',
      note: '',
      contract_type: 1,


      partner: '',
      code_contract:'',
      vat_contract: '',


      employee: '',
      team: '',
    }),
    partner:[],
    employees: [],
    categories: [],
    team: [],
    subCategories: [],
    url: null,
  }),
  computed: {
    ...mapGetters('operations', ['items','appInfo']),
  },
  created() {
    this.getEmployees()
    this.getCategories()
    this.getPartner()
    this.getTeams()
    this.getSubCatgories()
    this.loadHtml()
  },
  methods: {
    // add_CBcontract
    add_CBcontract() {
      this.html_contract += '<div class="form-group col-md-6 col-xl-3">';
      this.html_contract += '  <label for="category"';
      this.html_contract += '    >{{ $t("products.list.common.category") }}';
      this.html_contract += '    <span class="required">*</span></label';
      this.html_contract += '  >';
      this.html_contract += '    <v-select';
      this.html_contract += '    label="name"';
      this.html_contract += '    v-model="form.category"';
      this.html_contract += '    :options="categories"';
      this.html_contract += '    :class="{ "is-invalid": form.errors.has("category") }"';
      this.html_contract += '    name="category"';
      this.html_contract += '    :placeholder="$t("common.category_name_placeholder")"';
      this.html_contract += '    @input="RenderSubCategories"';
      this.html_contract += '  />';
      this.html_contract += '  <has-error :form="form" field="category" />';
      this.html_contract += '</div>';
      this.html_contract += '<div class="form-group col-md-6 col-xl-3">';
      this.html_contract += '  <label for="subCategory"';
      this.html_contract += '    >{{ $t("common.sub_category") }}';
      this.html_contract += '    <span class="required">*</span></label';
      this.html_contract += '  >';
      this.html_contract += '  <v-select';
      this.html_contract += '    v-model="form.subCategory"';
      this.html_contract += '    :options="subCategories"';
      this.html_contract += '    label="name"';
      this.html_contract += '    :class="{ "is-invalid": form.errors.has("subCategory") }"';
      this.html_contract += '    name="subCategory"';
      this.html_contract += '    :placeholder="$t("common.category_name_placeholder")"';
      this.html_contract += '  />';
      this.html_contract += '  <has-error :form="form" field="subCategory" />';
      this.html_contract += '</div>';
      this.html_contract += '<div class="form-group col-md-6 col-xl-3">';
      this.html_contract += '  <label for="timePost"';
      this.html_contract += '    >{{ $t("products.list.common.time_post") }}';
      this.html_contract += '    <span class="required">*</span></label';
      this.html_contract += '  >';
      this.html_contract += '  <input';
      this.html_contract += '    id="timePost"';
      this.html_contract += '    type="time"';
      this.html_contract += '    name="timePost"';
      this.html_contract += '    v-model="form.timePost"';
      this.html_contract += '    class="form-control"';
      this.html_contract += '    :class="{ "is-invali": form.errors.has("timePost") }"';
      this.html_contract += '  />';
      this.html_contract += '  <has-error :form="form" field="timePost" />';
      this.html_contract += '</div>';
      this.html_contract += '<div class="form-group col-md-6 col-xl-3">';
      this.html_contract += '  <label for="post_number"';
      this.html_contract += '    >{{ $t("common.post_number") }}';
      this.html_contract += '    <span class="required">*</span></label';
      this.html_contract += '  >';
      this.html_contract += '  <input';
      this.html_contract += '    id="post_number"';
      this.html_contract += '    type="number"';
      this.html_contract += '    min="0"';
      this.html_contract += '    name="post_number"';
      this.html_contract += '    v-model="form.post_number"';
      this.html_contract += '    class="form-control"';
      this.html_contract += '    :placeholder="$t("common.post_number_placeholder")"';
      this.html_contract += '    :class="{ "is-invalid": form.errors.has("post_number") }"';
      this.html_contract += '  />';
      this.html_contract += '  <has-error :form="form" field="post_number" />';
      this.html_contract += '</div>';
      document.querySelector('.combo_contract').appendChild(this.html_contract);
    },
    // SelectContractType
    SelectContractType(e) {
      const value = e.target.value;
      if(value == 0)
      {
        this.cb_contract = true;
      }
      else
      {
        this.cb_contract = false;
      }
    },
    // Get subcatgories
    async getSubCatgories() {
      await this.$store.dispatch('operations/allData', {
        path: '/api/all-product-sub-categories',
      })
    },
    // show modal
    showModal() {
      this.isActive = true;
      document.querySelector(".modal-backdrop").style.display = 'block';
    },
    // get all product categories
    RenderSubCategories() {
      const category_id = this.form.category.id;
      fetch(window.location.origin + '/api/all-product-sub-categories/' + category_id)
      .then(res => res.json())
      .then(res=>{this.subCategories = res.data})
      },
    // get all sub
    async getCategories() {
      const { data } = await this.form.get(
        window.location.origin + '/api/all-product-categories'
      )
      this.categories = data.data;
    },
    // get all partner
    async getPartner() {
      const { data } = await this.form.get(
        window.location.origin + '/api/all-clients'
      )
      this.partner = data.data
    },
    // get all employees
    async getEmployees() {
      const { data } = await this.form.get(
        window.location.origin + '/api/all-employees'
      )
      this.employees = data.data
    },
    // get item code
    async getTeams() {
      const { data } = await this.form.get(
        window.location.origin + '/api/all-departments'
      )
      this.team = data.data
    },
    // vue file upload
    onFileChange(e) {
      const file = e.target.files[0]
      const reader = new FileReader()
      if (
        file.size < 2111775 &&
        (file.type === 'image/jpeg' ||
          file.type === 'image/png' ||
          file.type === 'image/gif')
      ) {
        reader.onloadend = (file) => {
          this.form.image = reader.result
        }
        reader.readAsDataURL(file)
        this.url = URL.createObjectURL(file)
      } else {
        Swal.fire(
          this.$t('common.error'),
          this.$t('common.image_error'),
          'error'
        )
      }
    },

    // save product
    async saveProduct() {
      await this.form
        .post(window.location.origin + '/api/products')
        .then(() => {
          toast.fire({
            type: 'success',
            title: this.$t('products.list.create.success_msg'),
          })
          this.$router.push({ name: 'products.index' })
        })
        .catch(() => {
          toast.fire({ type: 'error', title: this.$t('common.error_msg') })
        })
    },

    // save client
    async saveClient() {
      await this.form
        .post(window.location.origin + '/api/clients')
        .then(() => {
          toast.fire({
            type: 'success',
            title: this.$t('clients.create.success_msg'),
          })
          this.getPartner();
          this.isActive = false;
          document.querySelector('.close').click();
          fetch(window.location.origin + '/api/all-clients')
          .then(res => res.json())
          .then(data =>  this.form.partner = (data.data)[0])
        })
        .catch(() => {
          toast.fire({ type: 'error', title: this.$t('common.error_msg') })
        })
    },
  },
}
</script>

<style lang="scss" scoped>
#exampleModal .modal-dialog
{
  max-width: 600px !important;
}
.help-block.invalid-feedback
{
  display: block;
}
</style>
