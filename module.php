<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wish_Spotlight_Widget extends Widget_Base {

    public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_script( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', [ 'elementor-frontend' ], '1.8.1', true );
		wp_register_script( 'wishqa-spotlight-script', get_stylesheet_directory_uri() . '/includes/elementor/wishqa_spotlight/js/script.js', array( 'jquery' ), _S_VERSION, true );

		wp_register_style( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css' );
		wp_register_style( 'slick-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css' );
	}

    public function get_script_depends() {
		return [ 'slick', 'wishqa-spotlight-script' ];
	}

	public function get_style_depends() {
		return [ 'slick', 'slick-theme' ];
	}

	public function get_name() {
		return 'wishqa_spotlight';
	}

	public function get_title() {
		return __( 'Wish Spotlight', 'wishqa' );
	}

	public function get_icon() {
		return 'fa fa-book';
	}

	public function get_categories() {
		return [ 'wishqa-widget' ];
	}

    private function get_available_tags() {
        $terms = get_terms( array(
            'taxonomy' => 'post_tag',
            'hide_empty' => false,
        ) );

        $options = [];

		foreach ( $terms as $term ) {
			$options[ $term->term_id ] = $term->name;
		}

		return $options;
    }

    protected function _register_controls() {
        $this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Slides', 'wishqa' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

            $article_tag = $this->get_available_tags();
            $this->add_control(
                'post_tags',
                [
                    'label' => __( 'Select Tags', 'wishqa' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $article_tag,
                    'label_block' => 'true',
                ]
            );


			$this->add_control(
				'featured_number_post',
				[
					'label' => __( 'Number Post', 'wishqa' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 200,
					'step' => 1,
					'default' => 6,
					'dynamic'       => array(
						'active' => true,
					),
				]
			);

            $this->add_control(
                'posts_per_page',
                [
                    'label' => __( 'Posts Per Page', 'wishqa' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 50,
                    'step' => 1,
                    'default' => 6,
                    'dynamic'       => array(
                        'active' => true,
                    ),
                ]
            );

            $this->add_responsive_control(
                'height',
                [
                    'label' => __( 'Height', 'wishqa' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 5,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 630,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wish-spotlight__item' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

		$this->end_controls_section();

        // Slides Style
        $this->start_controls_section(
			'slides_style',
			[
				'label' => __( 'Slides', 'wishqa' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

            $this->add_responsive_control(
                'slides_spacing',
                [
                    'label' => __( 'Spacing', 'wishqa' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wish-spotlight__item' => 'margin: 0 {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'slides_border_radius',
                [
                    'label' => __( 'Border Radius', 'wishqa' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px' ],
                    'selectors' => [
                        '{{WRAPPER}} .wish-spotlight__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'slides_padding',
                [
                    'label' => __( 'Padding', 'wishqa' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wish-spotlight__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Title Style
        $this->start_controls_section(
			'title_style',
			[
				'label' => __( 'Title', 'wishqa' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Title Color', 'wishqa' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => \Elementor\Scheme_Color::get_type(),
                        'value' => \Elementor\Scheme_Color::COLOR_1,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wish-spotlight__title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __( 'Typography', 'wishqa' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .wish-spotlight__title',
                ]
            );

        $this->end_controls_section();

        // Button Style
        $this->start_controls_section(
			'button_style',
			[
				'label' => __( 'Button', 'wishqa' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'button_typography',
                    'label' => __( 'Typography', 'wishqa' ),
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .wish-spotlight__button',
                ]
            );

            $this->add_control(
                'button_border_width',
                [
                    'label' => __( 'Border Width', 'wishqa' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 1,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wish-spotlight__button' => 'border: {{SIZE}}{{UNIT}} solid',
                    ],
                ]
            );

            $this->add_control(
                'button_border_radius',
                [
                    'label' => __( 'Border Radius', 'wishqa' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wish-spotlight__button' => 'border-radius: {{SIZE}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'button_padding',
                [
                    'label' => __( 'Padding', 'wishqa' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wish-spotlight__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs(
                'style_button_tabs'
            );
    
                $this->start_controls_tab(
                    'style_button_normal_tab',
                    [
                        'label' => __( 'Normal', 'wishqa' ),
                    ]
                );
    
                    $this->add_control(
                        'button_text_color',
                        [
                            'label' => __( 'Text Color', 'wishqa' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => \Elementor\Scheme_Color::get_type(),
                                'value' => \Elementor\Scheme_Color::COLOR_1,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .wish-spotlight__button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_bg_color',
                        [
                            'label' => __( 'Background Color', 'wishqa' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => \Elementor\Scheme_Color::get_type(),
                                'value' => \Elementor\Scheme_Color::COLOR_1,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .wish-spotlight__button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_border_color',
                        [
                            'label' => __( 'Border Color', 'wishqa' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => \Elementor\Scheme_Color::get_type(),
                                'value' => \Elementor\Scheme_Color::COLOR_1,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .wish-spotlight__button' => 'border-color: {{VALUE}}',
                            ],
                        ]
                    );
    
                $this->end_controls_tab();
    
                $this->start_controls_tab(
                    'style_button_hover_tab',
                    [
                        'label' => __( 'Hover', 'wishqa' ),
                    ]
                );

                    $this->add_control(
                        'button_text_color_hover',
                        [
                            'label' => __( 'Text Color', 'wishqa' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => \Elementor\Scheme_Color::get_type(),
                                'value' => \Elementor\Scheme_Color::COLOR_1,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .wish-spotlight__button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_bg_color_hover',
                        [
                            'label' => __( 'Background Color', 'wishqa' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => \Elementor\Scheme_Color::get_type(),
                                'value' => \Elementor\Scheme_Color::COLOR_1,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .wish-spotlight__button:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_border_color_hover',
                        [
                            'label' => __( 'Border Color', 'wishqa' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'scheme' => [
                                'type' => \Elementor\Scheme_Color::get_type(),
                                'value' => \Elementor\Scheme_Color::COLOR_1,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .wish-spotlight__button:hover' => 'border-color: {{VALUE}}',
                            ],
                        ]
                    );
    
                $this->end_controls_tab();
    
            $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $source	= $settings['source'];
        $post_tags   = $settings['post_tags'];
        $numberposts = $settings['featured_number_post'];
        $post_per_page   = $settings['posts_per_page'];

        $args = array(
            'post_type' => array( 'post', 'news', 'blogpost', 'young_innovators', 'innovations', 'reports' ),
            'post_status' => 'publish',
            'numberposts' => $numberposts,
            'posts_per_page' => $post_per_page,
            'order' => 'DESC',
            //'tag' => 'spotlight',
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy'  => 'post_tag',
                    'operator'  => 'IN', 
                    'terms'		=> $post_tags,
                ),
            ),
            
        );

        $posts = new \WP_Query( $args );

        if ( $posts->have_posts() ) {
			echo '<div class="wish-spotlight__wrapper ">';
            
			while( $posts->have_posts() ): $posts->the_post();
                $post_id   = get_the_ID();
                $post_type = get_post_type( $post_id );
                $title = get_the_title( $post_id );
                if( $post_type == 'young_innovators'){
                    $image = get_field('innovator_picture');
                }elseif( $post_type == 'innovations' ){
                    $image = get_field('featured_image');
                }elseif( $post_type == 'reports' ){
                    if(get_locale() == "en_US"){
                        $image = get_field('cover_page_english');
                    }else{
                        $image = get_field('cover_page_arabic');
                    }
                    
                } else{
                    $image = get_the_post_thumbnail_url( $post_id );
                }
                
                $link = get_the_permalink();

                echo '<div class="wish-spotlight__item elementor-repeater-item-' . $post_id . '">';
                    echo '<div class="wish-spotlight__bg" style="background-image: url(' . $image . ');"></div>';
                    echo '<div class="elementor-background-overlay"></div>';
                    echo sprintf('
                        <a href="%s" class="wish-spotlight__inner">
                            <div class="wish-spotlight__content">
                                <h3 class="wish-spotlight__title">%s</h3>
                                <div class="wish-spotlight__button-wrapper">
                                <div class="elementor-button wish-spotlight__button elementor-size-md">Read More</div></div>
                            </div>
                        </a>
                    ', $link,  $title );

                echo '</div>';
            endwhile;
			echo '</div>';
		}else{
            echo 'No Post(s) Found';
        }
        wp_reset_postdata();
    }
}