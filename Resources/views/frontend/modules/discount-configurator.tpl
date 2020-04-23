{block name="frontend_freju_discount_configurator"}
    <style>
        #freju-discount-configurator {
            margin: 24px 0;
        }

        .freju-dropdown {
            display: block;
            width: 100% !important;
        }
    </style>

    <div id="freju-discount-configurator">
        <h2>Je mehr Sie bestellen, desto höher Ihr Bundle-Bonus</h2>
        <dropdown :products="bundleProducts"></dropdown>

        <h3>Bundle-Produkte im Warenkorb</h3>
        <product-listing :products="basketProducts" type="cart"></product-listing>
    </div>

    <!-- development version, includes helpful console warnings -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- production version, optimized for size and speed -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/vue"></script> -->

    <script>
        Vue.component('dropdown', {
            props: {
                products: Array
            },

            data: function() {
                return {
                    searchRequest: '',
                    showResults: true
                };
            },

            computed: {
                results: function() {
                    var vm = this;

                    if(this.products && this.searchRequest !== '')
                        return this.products.filter(function(el) {
                            return (
                                (el.name && el.name.toUpperCase().includes(vm.searchRequest.toUpperCase()))
                                || (el.ean && el.ean.toString().includes(vm.searchRequest.toUpperCase()))
                            );
                        });

                    return [];
                }
            },

            template:
                    '<div class="freju-dropdown-wrapper">' +
                        '<input type="text" class="freju-dropdown" placeholder="Nach Artikeln suchen…" v-model="searchRequest">' +
                        '<div class="search-results" v-if="results[0]">' +
                            '<div class="search-results-header">' +
                                '<h3>Suchergebnisse</h3>' +
                                '<div class="close-button">+</div>' +
                            '</div>' +
                            '<product-listing :products="results" type="search"></product-listing>' +
                        '</div>' +
                    '</div>',

            methods: {
                filterMembers() {
                    let results = this.userList

                    results = results.filter(el => {
                        return el.name && ( (el.name.first && el.name.first.toUpperCase().includes(this.filterData.name.toUpperCase())) ||  (el.name.last && el.name.last.toUpperCase().includes(this.filterData.name.toUpperCase()) ))
                    })

                    this.shownUserList = results
                },
            }
        });

        Vue.component('list-item', {
            delimiters: ['%%', '%%'],

            data: function() {
                return {
                    quantity: 1
                };
            },

            props: {
                product: Object,
                type: String
            },

            computed: {
                price: function() {
                    return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(this.product.price);
                }
            },

            template:
                '<div class="freju-list-item">' +
                    '<input class="quantity" :value="product.quantity" @change="changeQuantity" v-if="type == \'cart\'">' +
                    '<div class="image" :style="\'background-image: url(\' + product.image + \')\'" />' +
                    '<div class="details">' +
                        '<div class="name">%% product.name %%</div>' +
                        '<div class="ean">EAN: %% product.ean %%</div>' +
                        '<div class="shipping-information">%% product.shippingInfo %%</div>' +
                    '</div>' +
                    '<div class="cart-details">' +
                        '<div class="price">%% price %%</div>' +
                        '<div class="add-to-cart-wrapper" v-if="type == \'search\'">' +
                            '<input class="quantity" v-model="quantity">' +
                            '<button class="add-to-cart-button" @click="addToCart">In den Warenkorb</button>' +
                        '</div>' +
                    '</div>' +
                    '<div class="close-button" v-if="type == \'cart\'" @click="removeFromCart">+</div>' +
                '</div>',

            methods: {
                addToCart: function() {
                    fetch('/checkout/ajaxAddArticleCart?sAdd=orderNumber')
                        .then(function(response) {

                        });
                },

                removeFromCart: function() {
                    fetch('/checkout/ajaxAddArticleCart?sAdd=orderNumber')
                            .then(function(response) {

                            });
                },

                changeQuantity: function() {
                    fetch('/checkout/ajaxAddArticleCart?sAdd=orderNumber')
                            .then(function(response) {

                            });
                }
            }
        });

        Vue.component('product-listing', {
            props: {
                products: Array,
                type: String
            },

            template:
                '<div class="freju-product-listing">' +
                    '<list-item v-for="product in products" :key="product.id" :product="product" :type="type"></list-item>' +
                '</div>'
        });

        Vue.component('discount-list', {

        });

        new Vue({
            el: '#freju-discount-configurator',
            delimiters: ['%%', '%%'],

            data: {
                bundleProducts: [
                    {
                        id: 1,
                        image: 'http://localhost:8888/media/image/5b/48/11/Beistelltisch-weiss-schraeg_200x200.jpg',
                        name: 'Canon Camera',
                        price: 3499,
                        bonus: 0.08,
                        ean: 1234567890,
                        shippingInfo: 'Sofort Lieferbar'
                    },
                    {
                        id: 2,
                        image: 'http://localhost:8888/media/image/90/82/c4/Briefkasten_200x200.jpg',
                        name: 'Nikon Camera',
                        price: 1599,
                        bonus: 0.2,
                        ean: 243514567,
                        shippingInfo: 'Sofort Lieferbar'
                    },
                    {
                        id: 3,
                        image: 'http://localhost:8888/media/image/5b/48/11/Beistelltisch-weiss-schraeg_200x200.jpg',
                        name: 'Canon Camera Gehäuse',
                        price: 499,
                        bonus: 0.08,
                        ean: 1234567890,
                        shippingInfo: 'Sofort Lieferbar'
                    },
                    {
                        id: 4,
                        image: 'http://localhost:8888/media/image/90/82/c4/Briefkasten_200x200.jpg',
                        name: 'Nikon Camera Stativ',
                        price: 299,
                        bonus: 0.2,
                        ean: 243514567,
                        shippingInfo: 'Sofort Lieferbar'
                    }
                ],

                basketProducts: [
                    {
                        id: 1,
                        image: 'http://localhost:8888/media/image/5b/48/11/Beistelltisch-weiss-schraeg_200x200.jpg',
                        name: 'Canon Camera',
                        price: 3499,
                        bonus: 0.08,
                        ean: 1234567890,
                        shippingInfo: 'Sofort Lieferbar',
                        quantity: 1
                    },
                    {
                        id: 2,
                        image: 'http://localhost:8888/media/image/90/82/c4/Briefkasten_200x200.jpg',
                        name: 'Nikon Camera',
                        price: 1599,
                        bonus: 0.2,
                        ean: 243514567,
                        shippingInfo: 'Sofort Lieferbar',
                        quantity: 2
                    }
                ]
            }
        });
    </script>
{/block}

