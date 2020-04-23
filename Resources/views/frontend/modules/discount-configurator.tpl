{block name="frontend_freju_discount_configurator"}
    <style>
        #freju-discount-configurator {
            margin: 24px 0;
        }
        #freju-discount-configurator h2 {
            font-size: 24px;
            font-weight: 400;
            margin-bottom: 24px;
            margin-top: 36px;
        }
        #freju-discount-configurator h3 {
            font-size: 20px;
            font-weight: 400;
            margin: 16px 0;
        }
        #freju-discount-configurator button {
            background: #3b5999;
            margin: 12px;
            border-radius: 5px;
            padding: 12px 16px;
            color: #fff;
            border: none;
            font-size: 16px;
            transition: all 0.25s ease;
        }
        #freju-discount-configurator button:hover {
            opacity: 0.85;
        }
        #freju-discount-configurator .close-button {
            transform: rotate(45deg);
            font-size: 40px;
            transition: all 0.25s ease;
            cursor: pointer;
            margin-left: 16px;
        }
        #freju-discount-configurator .close-button:hover {
            transform: scale(1.2) rotate(45deg);
        }
        .freju-dropdown {
            display: block;
            width: 100% !important;
            background: #fbfbfb !important;
            border: 1px solid #ebebeb !important;
            box-shadow: none !important;
            padding: 16px 20px !important;
            font-size: 16px !important;
            margin: 16px 0 !important;
            border-radius: 5px !important;
        }
        .freju-dropdown-wrapper .search-results-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .freju-list-item {
            display: flex;
            align-items: center;
            margin-top: 16px;
            border-radius: 5px;
            background: #fbfbfb;
            padding: 24px;
            cursor: pointer;
        }
        .freju-list-item .quantity {
            border-radius: 5px;
            background: #ebebeb;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            text-align: center;
            max-width: 60px;
            outline: none;
        }
        .freju-list-item .image {
            width: 150px;
            height: 150px;
            background-size: cover;
            margin: 0 24px;
            border-radius: 10px;
        }
        .freju-list-item .details {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            font-size: 16px;
        }
        .freju-list-item .details .name {
            font-size: 20px;
            font-weight: 600;
            padding-bottom: 8px;
        }
        .freju-list-item .details .shipping-information {
            background: rgba(57,88,154,0.3);
            color: #39589a;
            padding: 6px 10px;
            border-radius: 4px;
            font-weight: 600;
            margin-top: 8px;
            display: inline-block;
        }
        .freju-list-item .cart-details {
            margin-left: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .freju-list-item .cart-details .price {
            font-size: 20px;
        }
        .freju-list-item .cart-details .add-to-cart-wrapper {
            margin-top: 12px;
            display: flex;
        }
        .freju-list-item .cart-details .add-to-cart-wrapper .add-to-cart-button {
            margin: 0 !important;
            margin-left: 8px !important;
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

            mounted: function() {
                if(this.type == 'cart') {
                    this.quantity = this.product.quantity;
                }
            },

            template:
                '<div class="freju-list-item" @click="goToUrl(product.url)">' +
                    '<input class="quantity" v-model="quantity" @change="changeQuantity" @click.stop="" v-if="type == \'cart\'">' +
                    '<div class="image" :style="\'background-image: url(\' + product.image + \')\'" />' +
                    '<div class="details">' +
                        '<div class="name">%% product.name %%</div>' +
                        '<div class="ean">EAN: %% product.ean %%</div>' +
                        '<div class="shipping-information">%% product.shippingInfo %%</div>' +
                    '</div>' +
                    '<div class="cart-details">' +
                        '<div class="price">%% price %%</div>' +
                        '<div class="add-to-cart-wrapper" v-if="type == \'search\'">' +
                            '<input class="quantity" v-model="quantity" @click.stop="">' +
                            '<button class="add-to-cart-button" @click.stop="addToCart">In den Warenkorb</button>' +
                        '</div>' +
                    '</div>' +
                    '<div class="close-button" v-if="type == \'cart\'" @click.stop="removeFromCart">+</div>' +
                '</div>',

            methods: {
                addToCart: function() {
                    fetch('/checkout/ajaxAddArticleCart?sAdd=' + this.product.ordernumber + '&sQuantity=' + this.quantity)
                        .then((response) => {
                            return response.json();
                        }).then((data) => {
                            viewModel.data.bundleProducts.push(this.product);
                        });
                },

                removeFromCart: function() {
                    console.log(this.product);

                    fetch('checkout/ajaxDeleteArticleCart/sDelete/' + this.product.id, {
                        method: 'POST'
                    })
                    .then((response) => {
                        return response.json();
                    }).then((data) => {
                        viewModel.data.bundleProducts = viewModel.data.bundleProducts.filter(function(item) {
                            return item.id !== this.product.id;
                        });
                    });
                },

                changeQuantity: function() {
                    fetch('/checkout/changeQuantity/sTargetAction/cart?sArticle=' + this.product.id + '&sQuantity=' + this.quantity)
                        .then((response) => {
                            return response.json();
                        }).then((data) => {
                            console.log(data);
                        });
                },

                goToUrl: function(url) {
                    location.href = url;
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

        var viewModel = new Vue({
            el: '#freju-discount-configurator',
            delimiters: ['%%', '%%'],

            data: {
                bundleProducts: [],
                basketProducts: []
            },

            mounted() {
                fetch('/frontend/bundles/configurator')
                        .then((response) => {
                            return response.json();
                        })
                        .then((data) => {
                            this.bundleProducts = data;

                            fetch('/frontend/bundles/basket')
                                    .then((response) => {
                                        return response.json();
                                    })
                                    .then((data) => {
                                        var vm = this;

                                        this.basketProducts = data.content.filter(function(item) {
                                            return vm.bundleProducts.some(function(el) {
                                                return el.id == item.articleID;
                                            });
                                        });

                                        this.basketProducts.map(function(item) {
                                            item.name = item.articlename;
                                            item.id = item.articleID;
                                            item.price = parseFloat(item.price);
                                            item.url = item.linkDetails;
                                            item.image = item.image.source;

                                            return item;
                                        });

                                        console.log(this.basketProducts);
                                    });
                        });
            },

            methods: {

            }
        });
    </script>
{/block}

