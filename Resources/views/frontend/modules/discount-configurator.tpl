{block name="frontend_freju_discount_configurator"}
    <style>
        [v-cloak] {
            display: none;
        }
        #freju-discount-configurator {
            margin: 24px 0;
            padding: 24px;
            background: #ebebeb;
            border-radius: 10px;
            color: #5f7285;
        }
        #freju-discount-configurator h1,
        #freju-discount-configurator h2,
        #freju-discount-configurator h3 {
            text-transform: uppercase;
        }

        #freju-discount-configurator h1 {
            font-weight: 600;
            margin-top: 12px;
        }
        #freju-discount-configurator h2 {
            font-size: 24px;
            font-weight: 400;
            margin: 24px 0;
        }
        #freju-discount-configurator h3 {
            font-size: 20px;
            font-weight: 400;
            margin: 16px 0;
        }
        #freju-discount-configurator .strike {
            text-decoration: line-through;
            color: rgba(95, 114, 133, 0.7);
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
            outline: none;
        }
        #freju-discount-configurator button:hover {
            opacity: 0.85;
        }
        #freju-discount-configurator .close-button {
            transform: rotate(45deg);
            font-size: 40px;
            transition: all 0.25s ease;
            cursor: pointer;
        }
        #freju-discount-configurator .close-button:hover {
            transform: scale(1.2) rotate(45deg);
        }
        #freju-discount-configurator .empty-state {
            font-size: 16px;
            padding: 20px;
            border-radius: 5px;
            background: #fbfbfb;
        }
        .freju-footer {
            display: block;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-top: 12px;
        }
        .freju-footer button {
            margin-right: 0 !important;
        }
        .freju-footer .total {
            font-size: 16px;
            padding: 12px;
            background: #fff;
            border-radius: 5px;
            margin-right: 12px;
        }
        .freju-footer .total .number {
            font-weight: 600;
        }
        .freju-footer .total .number.green {
            color: #66aa66;
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
        .freju-dropdown-wrapper .search-results {
            background: #dbdbdb;
            margin-left: -24px;
            margin-right: -24px;
            padding: 24px;
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
            background-size: contain;
            background-repeat: no-repeat;
            background-color: #fff;
            background-position: center center;
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
            margin-right: 16px;
        }
        .freju-list-item .cart-details .add-to-cart-wrapper {
            margin-top: 12px;
            display: flex;
        }
        .freju-list-item .cart-details .add-to-cart-wrapper .add-to-cart-button {
            margin: 0 !important;
            margin-left: 8px !important;
            max-height: 45px;
        }

    </style>

    <div id="freju-discount-configurator" v-cloak>
        <h1>Freier Rabatt-Konfigurator</h1>
        <h2>Je mehr Sie bestellen, desto höher Ihr Bundle-Bonus</h2>
        <dropdown :products="bundleProducts" @add-to-cart="getBasket"></dropdown>

        <loader v-if="basketLoading"></loader>
        <div class="freju-basket" v-else>
            <h3>Bundle-Produkte im Warenkorb</h3>
            <product-listing :products="basketProducts" type="cart" v-if="basketProducts[0]" @remove-product="removeFromCart"></product-listing>
            <div class="empty-state" v-else>Bisher befinden sich keine Produkte mit Konfigurator-Rabatt in Ihrem Warenkorb</div>
        </div>

        <div class="freju-footer">
            <span class="total">
                Gesamte Ersparnis:
                <span class="number green">%% total.bonus %%</span>
            </span>
            <span class="total">
                Bundle-Preis:
                <span class="number">%% total.price %%</span>
            </span>
            <a href="/checkout/confirm">
                <button>Zur Kasse</button>
            </a>
        </div>
    </div>

    <!-- development version, includes helpful console warnings -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- production version, optimized for size and speed -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/vue"></script> -->

    <script>
        Vue.mixin({
            methods: {
                getStaging: function() {
                    return location.href.includes('staging') ? '/staging/' : '/';
                },

                getCSRFToken: function() {
                    return document.cookie.replace(/(?:(?:^|.*;\s*)__csrf_token-1\s*\=\s*([^;]*).*$)|^.*$/, "$1");
                }
            }
        });

        Vue.component('loader', {
            props: {
                noMargin: Boolean,
                small: Boolean,
                color: String
            },

            computed: {
                css: function() {
                    return {
                        margin: this.noMargin ? 0 : '24px auto',
                        background: 'none',
                        display: 'block',
                        shapeRendering: 'auto',
                        width: this.small ? '20px' : 'auto',
                        height: this.small ? '20px' : 'auto'
                    }
                },

                stroke: function() {
                    return this.color ? this.color : '#5f7285';
                }
            },

            template:
                    '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" :style="css" width="45px" height="45px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">\n' +
                        '<circle cx="50" cy="50" fill="none" :stroke="stroke" stroke-width="15" r="38" stroke-dasharray="179.0707812546182 61.690260418206066" transform="rotate(305.938 50 50)">\n' +
                            '<animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>\n' +
                        '</circle>' +
                    '</svg>'

        });

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
                        '<div class="search-results" v-if="searchRequest">' +
                            '<div class="search-results-header">' +
                                '<h3>Suchergebnisse</h3>' +
                                '<div class="close-button" @click="searchRequest = \'\'">+</div>' +
                            '</div>' +
                            '<product-listing :products="results" type="search" v-if="results[0]" @add-to-cart="addToCart"></product-listing>' +
                            '<div class="empty-state" v-else>Zu Ihrer Suche wurden leider keine passenden Produkte gefunden</div>' +
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

                addToCart: function() {
                    this.$emit('add-to-cart');
                }
            }
        });

        Vue.component('list-item', {
            delimiters: ['%%', '%%'],

            data: function() {
                return {
                    quantity: 1,
                    deleteLoading: false,
                    addToCartLoading: false
                };
            },

            props: {
                product: Object,
                type: String
            },

            computed: {
                price: function() {
                    var formatter = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' });

                    if(this.type == 'search') {
                        var oldPrice = formatter.format(this.product.price),
                            newPrice = formatter.format(this.product.price * (1 - this.product.bundlebonus / 100));
                    } else {
                        var oldPrice = formatter.format(this.product.oldPrice),
                            newPrice = formatter.format(this.product.newPrice);
                    }

                    return '<span class="strike">' + oldPrice + '</span> ' + newPrice;
                }
            },

            mounted: function() {
                if(this.type === 'cart') {
                    this.quantity = this.product.quantity;
                }
            },

            template:
                '<div class="freju-list-item" @click="goToUrl(product.url)">' +
                    '<input class="quantity" v-model="quantity" @keyup="changeQuantity" @click.stop="" v-if="type == \'cart\'">' +
                    '<div class="image" :style="\'background-image: url(\' + product.image + \')\'" />' +
                    '<div class="details">' +
                        '<div class="name">%% product.name %%</div>' +
                        '<div class="ean">EAN: %% product.ean %%</div>' +
                        '<div class="shipping-information">Sofort lieferbar</div>' +
                    '</div>' +
                    '<div class="cart-details">' +
                        '<div class="price" v-html="price"></div>' +
                        '<div class="add-to-cart-wrapper" v-if="type == \'search\'">' +
                            '<input class="quantity" v-model="quantity" @click.stop="">' +
                            '<button class="add-to-cart-button" @click.stop="addToCart">' +
                                '<loader v-if="addToCartLoading" color="#fff" noMargin small></loader>' +
                                '<div v-else>In den Warenkorb</div>' +
                            '</button>' +
                        '</div>' +
                    '</div>' +
                    '<loader v-if="deleteLoading" noMargin small></loader>' +
                    '<div class="close-button" v-else-if="type == \'cart\'" @click.stop="removeFromCart">+</div>' +
                '</div>',

            methods: {
                addToCart: function() {
                    var vm = this;

                    this.addToCartLoading = true;

                    $.get({
                        url: this.getStaging() + 'checkout/ajaxAddArticleCart?sAdd=' + this.product.ordernumber + '&sQuantity=' + this.quantity,
                        'appendCSRFToken': true,
                        'dataType': 'jsonp'
                    }, function(response) {
                        vm.addToCartLoading = false;

                        vm.$emit('add-to-cart');
                    });
                },

                removeFromCart: function() {
                    var vm = this;

                    this.deleteLoading = true;

                    $.post({
                        url: this.getStaging() + 'checkout/ajaxDeleteArticleCart/sDelete/' + this.product.cartItemId,
                        'appendCSRFToken': true,
                        'dataType': 'jsonp'
                    }, function(response) {
                        vm.$emit('remove-product', vm.$event, vm.product.id);
                    });
                },

                changeQuantity: function() {
                    if(this.quantity) {
                        $.post({
                            url: this.getStaging() + 'checkout/changeQuantity/sTargetAction/cart',
                            data: {
                                sArticle: this.product.cartItemId,
                                sQuantity: this.quantity,
                            },
                            'appendCSRFToken': true,
                            'dataType': 'jsonp'
                        }, function(response) {
                            // quantity changed
                        });
                    }
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
                    '<list-item v-for="product in products" :key="product.id" :product="product" :type="type" @remove-product="removeProduct" @add-to-cart="addToCart"></list-item>' +
                '</div>',

            methods: {
                removeProduct: function(event, productId) {
                    this.$emit('remove-product', this.$event, productId);
                },

                addToCart: function() {
                    this.$emit('add-to-cart');
                }
            }
        });

        Vue.component('discount-list', {

        });

        var viewModel = new Vue({
            el: '#freju-discount-configurator',
            delimiters: ['%%', '%%'],

            data: {
                bundleProducts: [],
                basketProducts: [],
                basketLoading: true
            },

            mounted() {
                this.getBundleProducts();
            },

            computed: {
                total: function() {
                    var formatter = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }),
                        oldPriceTotal = 0,
                        newPriceTotal = 0;

                    this.basketProducts.forEach(function(item) {
                        oldPriceTotal += item.oldPrice * parseInt(item.quantity);
                        newPriceTotal += item.newPrice * parseInt(item.quantity);
                    });

                    return {
                        price: formatter.format(newPriceTotal),
                        bonus: formatter.format(oldPriceTotal - newPriceTotal)
                    }
                }
            },

            methods: {
                getBundleProducts: function() {
                    fetch(this.getStaging() + 'frontend/bundles/configurator')
                            .then((response) => {
                                return response.json();
                            })
                            .then((data) => {
                                this.bundleProducts = data;

                                this.bundleProducts.map(function(item) {
                                    item.priceNet = item.price;
                                    item.price = item.price * (1 + item.tax / 100);
                                });

                                this.getBasket();
                            });
                },

                getBasket: function() {
                    this.basketLoading = true;

                    fetch(this.getStaging() + 'frontend/bundles/basket')
                            .then((response) => {
                                return response.json();
                            })
                            .then((data) => {
                                var vm = this;

                                console.log(data);

                                if(data.content && data.content[0]) {
                                    this.basketProducts = data.content.filter(function(item) {
                                        return vm.bundleProducts.some(function(el) {
                                            return el.id === item.articleID;
                                        });
                                    });

                                    this.basketProducts.map(function(item) {
                                        item.name = item.articlename;
                                        item.cartItemId = item.id,
                                                item.id = item.articleID;

                                        var bundleProduct = vm.bundleProducts.find(function(el) {
                                            return el.id === item.id;
                                        });

                                        item.oldPrice = parseFloat(bundleProduct.price);
                                        item.newPrice = parseFloat(item.priceNumeric);
                                        item.url = item.linkDetails;
                                        item.image = item.image.source;

                                        return item;
                                    });
                                }

                                this.basketLoading = false;
                            });
                },

                removeFromCart: function(event, productId) {
                    this.basketProducts = this.basketProducts.filter(function(item) {
                        return item.id !== productId;
                    });
                }
            }
        });
    </script>
{/block}

