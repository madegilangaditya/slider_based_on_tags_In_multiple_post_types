class WishSpotlightElementorHandler extends elementorModules.frontend.handlers.Base {
	
    getDefaultSettings() {
        return {
            selectors: {
                wrapper: '.wish-spotlight__wrapper',
            },
        };
    }

    getDefaultElements() {
        const selectors = ( this.getSettings('selectors') );

        return {
            $wrapper: this.$element.find(selectors.wrapper),
            $lang: document.documentElement.lang,
        };
    }

    bindEvents() {
        if(this.elements.$lang=="ar"){
            var rtl_status=true;
        }else{
            var rtl_status=false;
        }
        this.elements.$wrapper.slick({
			variableWidth: false,
			slidesToShow: 2,
			slidesToScroll: 2,
			autoplay: false,
			centerMode: false,
			focusOnSelect: true,
			autoplaySpeed: 7000,
			arrows: false,
			dots: true,
			infinite: false,
            rtl: rtl_status,
            responsive: [
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }
	
}

jQuery(window).on('elementor/frontend/init', () => {
    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(WishSpotlightElementorHandler, {
            $element,
        });
    };

    console.log(document.documentElement.lang);
    // Add our handler to the my-elementor Widget (this is the slug we get from get_name() in PHP)
    elementorFrontend.hooks.addAction('frontend/element_ready/wishqa_spotlight.default', addHandler);
})