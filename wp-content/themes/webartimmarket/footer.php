        <footer>
            <div class="footer-item-2">
                <div id="subscribe">
                    <div class="left-block">
                        <i class="subscribe-icon"></i>
                        <label for="subscribe-input" class="subscribe-text">Подписывайтесь на новости и акции</label>
                    </div><div class="right-block">
                        <input id="subscribe-input" type="text" class="subscribe-input" /><!--
                        --><button class="subscribe-button">подписаться</button>
                    </div>
                </div>
                <div class="footer-menu-items">
                    <div class="footer-menu-1 footer-menu-item">
                        <?php wp_nav_menu(['theme_location' => 'footer_1', 'container_class' => 'menu-container']) ?>
                    </div><!--
                    --><div class="footer-menu-2 footer-menu-item">
                    <?php wp_nav_menu(['theme_location' => 'footer_2', 'container_class' => 'menu-container']) ?>
                    </div><!--
                    --><div class="footer-menu-3 footer-menu-item">
                    <?php wp_nav_menu(['theme_location' => 'footer_3', 'container_class' => 'menu-container']) ?>
                    </div><!--
                    --><div class="footer-menu-4 footer-menu-item">
                    <?php wp_nav_menu(['theme_location' => 'footer_4', 'container_class' => 'menu-container']) ?>
                    </div>
                </div>
            </div><!--
            --><div class="footer-item-1">
                <div class="contacts-block">
                    <div class="phone"><i class="fa fa-phone"></i>+7 (921) 944 73 98</div>
                    <div class="address">г. Санкт-Петербург, БЦ "Охта", ул. Ворошилова, 2</div>
                </div><!--
                --><div class="social-block">
                    <a class="social fb" href="#"></a><!--
                    --><a class="social in" href="#"></a><!--
                    --><a class="social yt" href="#"></a><!--
                    --><a class="social vk" href="#"></a>
                </div>
            </div>
            <div id="copyright">2018 (с) Хобби Штучки, hobbyshtuchki.ru</div>
        </footer>
        </div>        
        <?php wp_footer(); ?>
    </body>
</html>