<template>

    <div>
        <div class="card">
            <div class="card-header">
                <h2>Manage Products</h2>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <a href="#" class="btn btn-primary" @click.prevent="add">
                            <i class="fas fa-plus-circle"></i> Add New
                        </a>
                    </div>
                    <div class="col-md-9">
                        <div class="form-inline justify-content-end">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" v-model="filters.keywords" v-on:keyup.enter="search" v-on:keyup.esc="clearText" class="form-control" placeholder="Search...">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" v-show="isSearching" v-on:click="clearText" type="button"><i class="fas fa-sync-alt"></i></button>
                                        <button class="btn btn-outline-secondary" type="button" v-on:click="search" :disabled="keywordsIsInvalid"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th @click="sort('name')" :class="classes('name')">Product Name</th>
                        <th @click="sort('category')" :class="classes('category')">Category</th>
                        <th @click="sort('price')" :class="classes('price')">Price</th>
                        <th width="100">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="product of productsPaginated"
                        :class="{ 'table-danger': removedProductId == product.id}"
                    >
                        <td>{{ product.name }}</td>
                        <td>{{ product.category }}</td>
                        <td>{{ product.price }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-secondary" @click.prevent="edit(product)">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="#" class="btn btn-sm btn-outline-danger" @click.prevent="remove(product)">
                                <i class="fas fa-times"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item" :class="{ disabled: isFirstPage }"><a class="page-link" href="#" @click.prevent="prev">Previous</a></li>
                        <li class="page-item" :class="{ active: currentPage === page }" v-for="page in pages">
                            <a class="page-link" href="#" @click.prevent="switchPage(page)">{{ page }}</a>
                        </li>
                        <li class="page-item" :class="{ disabled: isLastPage }"><a class="page-link" href="#" @click.prevent="next">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="product-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" ref="vuemodal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ modalTitle }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" v-model="product.name" class="form-control" :class="{ 'is-invalid': errors.name }">
                                <span class="invalid-feedback" v-if="errors.name">{{ errors.name[0] }}</span>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control" :class="{ 'is-invalid': errors.category_id }" v-model="product.category_id">
                                    <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                                </select>
                                <span class="invalid-feedback" v-if="errors.category_id">{{ errors.category_id[0] }}</span>
                            </div>
                            <div class="form-group">
                                <label>Price ($)</label>
                                <input type="number" v-model="product.price" class="form-control" :class="{ 'is-invalid': errors.price }">
                                <span class="invalid-feedback" v-if="errors.price">{{ errors.price[0] }}</span>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click.prevent="saveOrUpdate">{{ modalTextButton }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data: function () {
            return {
                products: [],
                categories: [],

                order: {
                    dir: 1,

                    column: 'name'
                },

                filters: {
                    name: '',

                    keywords: ''
                },

                isSearching: false,

                perPage: 10,

                currentPage: 1,

                product: {
                    id: null,
                    name: '',
                    category_id: '',
                    price: ''
                },

                isEdit: false,

                errors: {},

                removedProductId: null,
            }
        },

        mounted () {
            this.fetchProducts();
            this.fetchCategories();
        },

        computed: {
            productsPaginated () {
                let start = (this.currentPage - 1) * this.perPage
                let end = this.currentPage * this.perPage
                return this.productsSorted.slice(start, end)
            },

            productsSorted () {
                return this.productsFiltered.sort((a, b) => {
                    let left = a[this.order.column], right = b[this.order.column];

                    if (isNaN(left) && isNaN(right)) {
                        if (left < right) return -1 * this.order.dir;
                        else if (left > right) return 1 * this.order.dir;
                        else return 0;
                    } else {
                        return (left - right) * this.order.dir
                    }
                });
            },

            sortType () {
                return this.order.dir === 1 ? 'ascending' : 'descending'
            },

            keywordsIsInvalid () {
                return this.filters.keywords.length < 3;
            },

            productsFiltered () {
                let products = this.products;

                if (this.filters.name) {
                    let findName = new RegExp(this.filters.name, 'i');
                    products = products.filter(el => el.name.match(findName))
                }

                return products;
            },

            isFirstPage () {
                return this.currentPage === 1;
            },

            isLastPage () {
                return this.currentPage >= this.pages;
            },

            pages () {
                return Math.ceil(this.productsFiltered.length / this.perPage);
            },

            modalTitle () {
                return this.isEdit ? "Update Product" : "Add New Product"
            },

            modalTextButton () {
                return this.isEdit ? "Update" : "Save"
            }
        },

        methods: {
            fetchProducts () {
                axios.get('/products')
                    .then(({ data }) => {
                        this.products = data.data
                    })
            },

            fetchCategories () {
                axios.get('/categories')
                    .then(({ data }) => {
                        this.categories = data.data
                    })
            },

            add () {
                this.isEdit = false;

                this.product = {
                    id: null,
                    name: '',
                    category: '',
                    price: ''
                }

                $(this.$refs.vuemodal).modal('show');
            },


            edit (product) {
                this.product = Object.assign({}, product);

                this.isEdit = true

                $(this.$refs.vuemodal).modal('show');
            },

            saveOrUpdate () {
                if (this.isEdit) {
                    this.update();
                } else {
                    this.save();
                }
            },

            update () {
                this.product.price = this.product.price * 100;

                axios.put('/products/' + this.product.id, this.product)
                    .then(({ data }) => {
                        let index = this.products.findIndex(item => item.id === this.product.id);

                        this.products.splice(index, 1, data.data);

                        this.isEdit = false;

                        $(this.$refs.vuemodal).modal('hide');
                    })
                    .catch(({ response }) => {
                        this.errors = response.data.errors
                    })
            },

            save () {
                this.product.price = this.product.price * 100

                axios.post('/products', this.product)
                    .then(({ data }) => {
                        this.productsPaginated.unshift(data.data)

                        this.product = {
                            id: null,
                            name: '',
                            category: '',
                            price: ''
                        }

                        this.errors = {}

                        $(this.$refs.vuemodal).modal('hide');
                    })
                    .catch(( { response }) => {
                        this.errors = response.data.errors
                    })
            },

            remove (product) {
                if (confirm("Are you sure?")) {
                    axios.delete('/products/' + product.id)
                        .then(res => {
                            // store the product.id in removedProductId
                            this.removedProductId = product.id

                            // delay the execution for 1 second
                            // then set the removedProductId back to null to detach
                            // the table-danger class from <tr>
                            // after that remove the tr from UI
                            new Promise(resolve => setTimeout(resolve, 1000))
                                .then(() => {
                                    this.removedProductId = null

                                    let index = this.products.findIndex(item => item.id === product.id);

                                    this.products.splice(index, 1);
                                })
                        })
                }
            },

            switchPage (page) {
                this.currentPage = page
            },

            prev () {
                if (!this.isFirstPage) {
                    this.currentPage--;
                }
            },

            next () {
                if (!this.isLastPage) {
                    this.currentPage++;
                }
            },

            classes (column) {
                return [
                    'sort-control',
                    column === this.order.column ? this.sortType : ''
                ]
            },

            sort (column) {
                this.order.column = column;
                this.order.dir *= -1;
            },

            clearText () {
                this.filters.name = this.filters.keywords = "";

                this.isSearching = false
            },

            search () {
                if (!this.keywordsIsInvalid) {
                    this.filters.name = this.filters.keywords;

                    this.isSearching = true
                }
            }
        }
    }
</script>

