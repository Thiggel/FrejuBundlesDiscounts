{block name="frontend_freju_free-add-articles"}
    <style>
        .freju--article__free-add-articles {
            position: absolute;
            top: 0;
            right: 50%;
            transform: translate(50%,-50%);
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .freju--article__free-add-articles_images {
            display: flex;
        }
        .freju--article__free-add-articles_images img {
            width: 100px;
            height: 100px;
            border-radius: 50px;
            object-fit: cover;
            z-index: 1;
            border: 2px solid #ebebeb;
        }
        .freju--article__free-add-articles_images a:not(:first-child) {
            margin-left: -50px;
        }
        .freju--article__free-add-articles label {
            background: #ebebeb;
            color: #292929;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            z-index: 2;
            margin-top: -20px;
            text-align: center;
        }
        .freju--article__free-add-articles label a {
            color: #292929;
            font-weight: 600;
        }
        .freju--article__free-add-articles label:before {
            content: "+ Gratis ";
        }
        .freju--article__free-add-articles.listing {
            top: 0px;
            left: 8px;
            transform: none;
            z-index: 999999999;
            max-width: 180px;
        }
        .freju--article__free-add-articles.listing .freju--article__free-add-articles_images img {
            width: 50px;
            height: 50px;
        }
        .freju--article__free-add-articles.listing .freju--article__free-add-articles_images a:not(:first-child) {
            margin-left: -10px;
        }
        .freju--article__free-add-articles.listing label {
            font-size: 12px;
            padding: 10px 12px;
        }
    </style>

    {if isset($freeAddArticles) && is_array($freeAddArticles)}
        {$label = ""}

        <div class="freju--article__free-add-articles {$className}">
            <div class="freju--article__free-add-articles_images">
                {foreach $freeAddArticles as $key => $article}
                    {$name = $article['name']}
                    {$link = $article['url']}
                    {$img = $article['img']}


                    {if $key+1 == count($freeAddArticles)}
                        {$label = $label|cat:" und "}
                    {elseif $key !== 0}
                        {$label = $label|cat:","}
                    {/if}

                    {$label = $label|cat:" <a href='$link'>$name</a>"}


                    <a href="{$link}">
                        <img src="{$img}">
                    </a>
                {/foreach}
            </div>

            <label>{$label}</label>
        </div>
    {/if}
{/block}