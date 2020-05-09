{block name="frontend_freju_bundles"}
    {if isset($bundles) && is_array($bundles)}
        <style>
            .freju--bundles {
                margin-bottom: 16px;
                border-radius: 8px;
                overflow: hidden;
            }
            .freju--bundles__bundle div {
                display: flex;
                flex-wrap: wrap;
            }
            .freju--bundles__bundle_cell {
                width: 50%;
                font-size: 14px;
                padding: 12px 16px;
                background: rgb(198,206,224);
                color: #292929;
            }
            .freju--bundles__bundle_cell:first-child, .freju--bundles__bundle_cell:nth-child(2) {
                padding-top: 12px;
            }
            .freju--bundles__bundle_cell:last-child, .freju--bundles__bundle_cell:nth-last-child(2) {
                padding-bottom: 12px;
            }
            .freju--bundles__bundle_cell:nth-child(2n) {
                text-align: right;
            }
            .freju--bundles__bundle_cell.bundle {
                background: #fcfcfc;
            }
            .freju--bundles__bundle_cell.header {
                width: 100%;
            }
            .freju--bundles__bundle_cell a {
                color: #292929;
                font-weight: 600;
                width: 100%;
                text-align: center
            }
            .freju--bundles__bundle_cell span {
                width: 100%;
                text-align: center;
                margin: 0 !important;
            }
            .freju--bundles__bundle_cell .btn {
                background: #3b5999;
                border-radius: 5px;
                padding: 10px 16px;
                color: #fff;
                border: none;
                font-size: 14px;
                transition: all 0.25s ease;
                outline: none;
                text-align: center;
                margin-top: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .freju--bundles__bundle_cell .btn span {
                margin: 0;
            }
            .freju--bundles__bundle_cell .btn:hover {
                opacity: 0.85;
            }
            .freju--bundles__bundle_cell span {
                display: block;
                margin-bottom: 8px;
            }
        </style>

        <div class="freju--bundles">
            <div class="freju--bundles__bundle" id="freju--bundles">
                <div class="freju--bundles__bundle_cell header">Wählen Sie eine Sparkit-Variante:</div>

                {foreach $bundles as $bundle}
                    <bundle
                            name='{$bundle['name']}'
                            ordernumbers='{$bundle['ordernumbers']}'
                            :price="parseFloat({$bundle['totalPrice']})"
                            :bonus="parseFloat({$bundle['totalBonus']})"
                    >
                    </bundle>
                {/foreach}

            </div>

            <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

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

                Vue.component('bundle', {
                    delimiters: ['%%', '%%'],

                    props: {
                        name: String,
                        ordernumbers: String,
                        price: Number,
                        bonus: Number
                    },

                    computed: {
                        formattedPrice: function() {
                            var formatter = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' });

                            return formatter.format(this.price);
                        },

                        formattedBonus: function() {
                            var formatter = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' });

                            return formatter.format(this.bonus);
                        }
                    },

                    template:
                            '<div>' +
                                '<div class="freju--bundles__bundle_cell bundle" v-html="name"></div>'+
                                '<div class="freju--bundles__bundle_cell bundle">'+
                                    '<span>%% formattedPrice %%</span>'+
                                    '<span>(%% formattedBonus %% Gesamtersparnis)</span>'+
                                    '<a class="btn bundleAddToCart" @click="addToCart">' +
                                        '<loader v-if="isLoading" color="#fff" noMargin small></loader>'+
                                        '<span v-else>In den Warenkorb</span>'+
                                    '</a>'+
                                '</div>' +
                            '</div>',

                    data: function() {
                        return {
                            isLoading: false
                        }
                    },

                    methods: {
                        addToCart: function() {
                            this.isLoading = true;

                            ordernumberList = this.ordernumbers.split(',');
                            for(var i=0;i<ordernumberList.length; i++) {
                                vm = this;
                                $.get({
                                    url: vm.getStaging() + 'checkout/ajaxAddArticleCart?sAdd=' + ordernumberList[i],
                                    'appendCSRFToken': true,
                                    'dataType': 'jsonp'
                                }, function(response) {
                                    vm.isLoading = false;
                                });
                            }
                        }
                    }
                });

                var viewModel = new Vue({
                    el: '#freju--bundles',
                    delimiters: ['%%', '%%']
                });
            </script>
        </div>

    {/if}
{/block}

