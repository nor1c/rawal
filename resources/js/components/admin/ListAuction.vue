<template>
  <div>
    <div class="d-flex flex-column-fluid">
      <!--begin::Container-->
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="row">
              <div class="col-lg-12 col-xl-12">
                <div class="card card-custom gutter-b bg-transparent shadow-none border-0">
                  <div class="card-header align-items-center  border-bottom-dark px-0">
                    <div class="card-title mb-0">
                      <h3 class="card-label mb-0 font-weight-bold text-body">
                        Daftar Lelang
                      </h3>
                    </div>
                    <div class="icons d-flex">
                      <router-link to="/admin/add-purchase" class="btn ml-2 p-0 kt_notes_panel_toggle"
                        data-toggle="tooltip" title="" data-placement="right" data-original-title="Check out more demos"
                        v-if="$parent.permissions.includes('auction-manage')">
                        <span
                          class="bg-secondary h-30px font-size-h5 w-30px d-flex align-items-center justify-content-center  rounded-circle shadow-sm "
                          v-on:click="
                            display_form = !display_form
                          ">
                          <svg width="25px" height="25px" viewBox="0 0 16 16" class="bi bi-plus white"
                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                              d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z">
                            </path>
                          </svg>
                        </span>
                      </router-link>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 ">
                <div class="card card-custom gutter-b bg-white border-0">
                  <div class="card-body">
                    <div>
                      <div class=" table-responsive" id="printableTable">
                        <div id="purchase_wrapper" class="dataTables_wrapper no-footer">
                          <div class="dataTables_length" id="purchase_length"><label>Show
                              <select name="purchase_length" aria-controls="purchase" class="" v-model="limit"
                                v-on:change="fetchAuctions()">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                                <option value="500">500</option>
                                <option value="1000">1000</option>
                              </select> entries</label></div>

                          <div id="purchase_filter" class="dataTables_filter">
                            <!-- <label>Search:<input type="search" class="" placeholder="" aria-controls="purchase" v-model="searchParameter" @keyup="fetchAuctions()"></label> -->
                          </div>
                          <table id="purchase" class="display dataTable no-footer" purchase="grid">
                            <thead class="text-body">
                              <tr purchase="row">
                                <th class="sorting" tabindex="0" aria-controls="purchase" rowspan="1" colspan="1"
                                  aria-sort="ascending" aria-label="ID: activate to sort column descending"
                                  style="width: 31.25px;" @click="sorting('id')"
                                  :class="(this.$data.sortType == 'asc' || this.$data.sortType == 'ASC') && this.$data.sortBy == 'id' ? 'sorting_asc' : (this.$data.sortType == 'desc' || this.$data.sortType == 'DESC') && this.$data.sortBy == 'id' ? 'sorting_desc' : 'sorting'">
                                  ID
                                </th>
                                <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Action">
                                  Produk
                                </th>
                                <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Action">
                                  Kuantitas
                                </th>
                                <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Action">
                                  Durasi (jam)
                                </th>
                                <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Action">
                                  Harga Pembukaan
                                </th>
                                <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Action">
                                  Minimal Bid
                                </th>
                                <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Action">
                                  Kelipatan Bid
                                </th>
                                <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Action">
                                  Aktif?
                                </th>
                                <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Action">
                                  Aksi
                                </th>
                              </tr>
                            </thead>
                            <tbody class="kt-table-tbody text-dark">
                              <tr class="kt-table-row kt-table-row-level-0 odd" auction="row"
                                v-for="auction in auctions" v-bind:key="auction.id">
                                <td class="sorting_1">
                                  <router-link :to="'/admin/auction-detail/' + auction.auction_id">
                                    {{ auction.auction_id }}
                                  </router-link>
                                </td>
                                <td>
                                  {{ auction.product[0].title }}
                                </td>
                                <td>
                                  {{ auction.qty }} {{ auction.unit }}
                                </td>
                                <td>
                                  {{ auction.duration }} jam
                                </td>
                                <td>
                                  {{ auction.starting_price }}
                                </td>
                                <td>
                                  {{ auction.min_bid }}
                                </td>
                                <td>
                                  {{ auction.multiplier_bid }}
                                </td>
                                <td>
                                  <i v-if="auction.is_active == 1" class="fas fa-circle-check" style="color:green" />
                                  <i v-if="auction.is_active == 0" class="fas fa-circle-xmark" style="color:red" />
                                </td>
                                <router-link :to="'/admin/auction-detail/' + auction.auction_id">
                                  View
                                </router-link>
                              </tr>
                            </tbody>
                          </table>
                          <ul class="pagination pagination-sm m-0 float-right">
                            <li v-bind:class="[{ disabled: !pagination.prev_page_url }]"><a class="page-link" href="#"
                                @click="fetchAuctions(pagination.prev_page_url)">Previous</a></li>

                            <li class="disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page
                            }} of {{ pagination.last_page }}</a></li>

                            <li v-bind:class="[{ disabled: !pagination.next_page_url }]" class="page-item"><a
                                class="page-link" href="#" @click="fetchAuctions(pagination.next_page_url)">Next</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="offcanvas offcanvas-right kt-color-panel p-5 kt_notes_panel" v-if="display_form"
      :class="display_form ? 'offcanvas-on' : ''">
      <div class="offcanvas-header d-flex align-items-center justify-content-between pb-3">
        <h4 class="font-size-h4 font-weight-bold m-0">Buat Lelang Baru</h4>
        <a href="#" class="btn btn-sm btn-icon btn-light btn-hover-primary kt_notes_panel_close"
          v-on:click="display_form = 0">
          <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z">
            </path>
          </svg>
        </a>
      </div>
      <form id="myform">
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="text-dark">Nama </label>
              <input type="text" name="text" v-model="purchase.name" class="form-control" />
              <small class="form-text text-danger" v-if="errors.has('name')" v-text="errors.get('name')"></small>
            </div>
            <div class="form-group">
              <label class="text-dark">Direction </label>
              <select v-model="purchase.direction">
                <option value="ltr">LTR</option>
                <option value="rtl">RTL</option>
              </select>
              <small class="form-text text-danger" v-if="errors.has('direction')"
                v-text="errors.get('direction')"></small>
            </div>
            <div class="form-group">
              <label class="text-dark">Code </label>
              <input type="text" name="text" v-model="purchase.code" class="form-control" />
              <small class="form-text text-danger" v-if="errors.has('code')" v-text="errors.get('code')"></small>
            </div>
            <div class="form-group">
              <label class="text-dark">Directory </label>
              <input type="text" name="text" v-model="purchase.directory" class="form-control" />
              <small class="form-text text-danger" v-if="errors.has('directory')"
                v-text="errors.get('directory')"></small>
            </div>
            <div class="form-group">
              <input type="checkbox" name="text" v-model="purchase.is_default" id="is_default"
                class="form-check-input" />
              <label class="text-dark" for="is_default">Set as default </label>
              <small class="form-text text-danger" v-if="errors.has('is_default')"
                v-text="errors.get('is_default')"></small>
            </div>

          </div>
        </div>
        <button type="button" @click="addUpdatepurchase()" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</template>

<script>
import ErrorHandling from "./../../ErrorHandling";
export default {
  data() {
    return {
      display_form: 0,
      auctions: [],
      searchParameter: '',
      sortBy: 'id',
      sortType: 'ASC',
      limit: 10,
      error_message: '',
      edit: false,
      actions: false,
      pagination: {},
      request_method: "",
      is_default: "0",
      token: [],
      currency: [],
      errors: new ErrorHandling(),
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute("content")
    };
  },

  methods: {
    fetchCurrency() {
      this.$parent.loading = true;
      var token = localStorage.getItem('token');
      const config = {
        headers: {
          Authorization: `Bearer ${token}`
        }
      };
      var responseData = {};

      axios.get('/api/admin/currency?is_default=1', config)
        .then(res => {
          if (res.data.status == "Success") {
            this.currency = res.data.data;
          }
        })
        .finally(() => (this.$parent.loading = false));
    },
    fetchAuctions(page_url) {
      this.$parent.loading = true;
      let vm = this;
      page_url = page_url || "/api/admin/auction";
      var arr = page_url.split('?');

      if (arr.length > 1) {
        page_url += '&limit=' + this.limit;
      }
      else {
        page_url += '?limit=' + this.limit;
      }
      if (this.searchParameter != null) {
        page_url += '&searchParameter=' + this.searchParameter;
      }
      page_url += '&sortBy=' + this.sortBy + '&sortType=' + this.sortType;
      page_url += '&getSupplier=1&getPurchaseDetail=1&getProductDetail=1&getProductCombinationDetail=1&getWarehouse=1';

      var responseData = {};

      axios.get(page_url, this.token).then(res => {
        this.auctions = res.data.data;
        vm.makePagination(res.data.meta, res.data.links);
      })
        .finally(() => (this.$parent.loading = false));
    },

    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };

      this.pagination = pagination;
    },
    sorting(sortBy) {
      this.sortBy = sortBy;
      this.sortType = this.sortType == 'asc' || this.sortType == 'ASC' ? this.sortType = 'desc' : this.sortType = 'asc';
      this.fetchAuctions();
    },
    getCurrencyTitle() {
      return this.currency == null ? '' : '(' + this.currency.title + ')';
    }
  },
  mounted() {
    var token = localStorage.getItem('token');
    this.token = {
      headers: {
        Authorization: `Bearer ${token}`
      }
    };
    this.fetchCurrency();
    this.fetchAuctions();
  },
  props: ['loading'],
};
</script>
