/**
 * Duo Communications メインJavaScriptファイル
 * Author: Web Developer  
 * Version: 1.0.0
 */

(function($) {
    'use strict';

    // 定数定義
    const ANIMATION_DELAY = 200;
    const SCROLL_OFFSET = 50;
    const MOBILE_BREAKPOINT = 768;

    /**
     * 初期化処理
     */
    function init() {
        console.log('Duo Communications サイト初期化開始');
        
        setupScrollAnimations();
        setupBusinessCardHover();
        setupSmoothScrolling();
        setupMobileMenu();
        setupLoadingAnimations();
        setupFormValidation();
        setupLazyLoading();
        setupNewsLoadMore();
        
        console.log('Duo Communications サイト初期化完了');
    }

    /**
     * スクロールアニメーション設定
     */
    function setupScrollAnimations() {
        if (!window.IntersectionObserver) {
            // IntersectionObserver がサポートされていない場合のフォールバック
            $('.scroll-animate').addClass('animate');
            return;
        }

        const observerOptions = {
            threshold: 0.1,
            rootMargin: `0px 0px -${SCROLL_OFFSET}px 0px`
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    $(entry.target).addClass('animate');
                    
                    // MVVカードの段階的アニメーション
                    if ($(entry.target).hasClass('mvv-section')) {
                        animateMVVCards();
                    }
                    
                    // ビジネスカードのアニメーション
                    if ($(entry.target).hasClass('business-section')) {
                        animateBusinessCards();
                    }
                }
            });
        }, observerOptions);

        // アニメーション対象要素を監視
        $('.scroll-animate').each(function() {
            observer.observe(this);
        });
    }

    /**
     * MVVカードのアニメーション
     */
    function animateMVVCards() {
        $('.mvv-card').each(function(index) {
            const $card = $(this);
            setTimeout(function() {
                $card.addClass('animate-in');
            }, index * ANIMATION_DELAY);
        });
    }

    /**
     * ビジネスカードのアニメーション
     */
    function animateBusinessCards() {
        $('.business-card').each(function(index) {
            const $card = $(this);
            setTimeout(function() {
                $card.css({
                    'opacity': '1',
                    'transform': 'translateY(0)'
                });
            }, index * 100);
        });
    }

    /**
     * ビジネスカードのホバー効果
     */
    function setupBusinessCardHover() {
        const $businessCards = $('.business-card');
        const $businessBg = $('#businessBg');
        
        if ($businessBg.length === 0) return;

        $businessCards.on('mouseenter', function() {
            const bgImage = $(this).data('bg');
            if (bgImage && bgImage !== '' && bgImage !== 'undefined') {
                $businessBg.css({
                    'background-image': bgImage,
                    'opacity': '0.4'
                });
            }
            
            // 他のカードを薄くする
            $businessCards.not(this).css({
                'opacity': '0.6',
                'transform': 'scale(0.95)'
            });
            
            // ホバー中のカードを強調
            $(this).css({
                'opacity': '1',
                'transform': 'scale(1.05)'
            });
        });
        
        $businessCards.on('mouseleave', function() {
            $businessBg.css('opacity', '0.2');
            
            // 全てのカードを元に戻す
            $businessCards.css({
                'opacity': '1',
                'transform': 'scale(1)'
            });
        });

        // セクション全体から離れた時の処理
        $('.business-section').on('mouseleave', function() {
            $businessBg.css('opacity', '0.1');
            $businessCards.css({
                'opacity': '1',
                'transform': 'scale(1)'
            });
        });
    }

    /**
     * スムーススクロール設定
     */
    function setupSmoothScrolling() {
        $('a[href^="#"]').on('click', function(e) {
            const targetId = $(this).attr('href');
            const $targetElement = $(targetId);
            
            if ($targetElement.length) {
                e.preventDefault();
                
                const offsetTop = $targetElement.offset().top - 80; // ヘッダー分を調整
                
                $('html, body').animate({
                    scrollTop: offsetTop
                }, 800, 'easeInOutCubic');
            }
        });
    }

    /**
     * モバイルメニュー設定
     */
    function setupMobileMenu() {
        const $mobileMenuButton = $('.mobile-menu-button');
        const $mobileMenu = $('.mobile-menu');
        
        if ($mobileMenuButton.length && $mobileMenu.length) {
            $mobileMenuButton.on('click', function() {
                $(this).toggleClass('active');
                $mobileMenu.toggleClass('active');
                $('body').toggleClass('menu-open');
            });
            
            // メニュー項目クリック時にメニューを閉じる
            $mobileMenu.find('a').on('click', function() {
                $mobileMenuButton.removeClass('active');
                $mobileMenu.removeClass('active');
                $('body').removeClass('menu-open');
            });
        }

        // ウィンドウリサイズ時の処理
        $(window).on('resize', function() {
            if ($(window).width() > MOBILE_BREAKPOINT) {
                $('body').removeClass('menu-open');
                $mobileMenuButton.removeClass('active');
                $mobileMenu.removeClass('active');
            }
        });
    }

    /**
     * ローディング時のアニメーション
     */
    function setupLoadingAnimations() {
        $(window).on('load', function() {
            // ファーストビューのアニメーション
            $('.hero-content').css({
                'opacity': '1',
                'transform': 'translateY(0)'
            });
            
            // ローディングクラスを削除
            $('body').removeClass('loading').addClass('loaded');
        });
    }

    /**
     * フォームバリデーション
     */
    function setupFormValidation() {
        $('form').on('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showFormErrors(this);
            }
        });
        
        // リアルタイムバリデーション
        $('input[required], textarea[required]').on('blur', function() {
            validateInput(this);
        }).on('input', function() {
            if ($(this).hasClass('error')) {
                validateInput(this);
            }
        });
    }

    /**
     * フォームバリデーション実行
     */
    function validateForm($form) {
        let isValid = true;
        const $requiredInputs = $($form).find('input[required], textarea[required]');
        
        $requiredInputs.each(function() {
            if (!validateInput(this)) {
                isValid = false;
            }
        });
        
        return isValid;
    }

    /**
     * 個別入力項目のバリデーション
     */
    function validateInput(input) {
        let isValid = true;
        const $input = $(input);
        const value = $input.val().trim();
        
        // 必須チェック
        if (input.hasAttribute('required') && !value) {
            isValid = false;
            showInputError($input, '必須項目です');
        }
        // メールアドレス形式チェック
        else if (input.type === 'email' && value && !isValidEmail(value)) {
            isValid = false;
            showInputError($input, 'メールアドレスの形式が正しくありません');
        }
        // 電話番号形式チェック
        else if (input.type === 'tel' && value && !isValidPhone(value)) {
            isValid = false;
            showInputError($input, '電話番号の形式が正しくありません');
        }
        else {
            clearInputError($input);
        }
        
        return isValid;
    }

    /**
     * 入力エラー表示
     */
    function showInputError($input, message) {
        $input.addClass('error');
        
        let $errorElement = $input.parent().find('.error-message');
        if ($errorElement.length === 0) {
            $errorElement = $('<div class="error-message"></div>');
            $input.parent().append($errorElement);
        }
        
        $errorElement.text(message);
    }

    /**
     * 入力エラークリア
     */
    function clearInputError($input) {
        $input.removeClass('error');
        $input.parent().find('.error-message').remove();
    }

    /**
     * メールアドレス形式チェック
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * 電話番号形式チェック
     */
    function isValidPhone(phone) {
        const phoneRegex = /^[\d\-\(\)\s\+]+$/;
        return phoneRegex.test(phone);
    }

    /**
     * 遅延読み込み設定
     */
    function setupLazyLoading() {
        const $lazyImages = $('img[data-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const $img = $(entry.target);
                        $img.attr('src', $img.data('src'));
                        $img.removeClass('lazy');
                        imageObserver.unobserve(entry.target);
                    }
                });
            });
            
            $lazyImages.each(function() {
                imageObserver.observe(this);
            });
        } else {
            // IntersectionObserverが利用できない場合のフォールバック
            $lazyImages.each(function() {
                const $img = $(this);
                $img.attr('src', $img.data('src'));
                $img.removeClass('lazy');
            });
        }
    }

    /**
     * ニュース「もっと見る」機能
     */
    function setupNewsLoadMore() {
        let offset = 4; // 初期表示件数
        
        $(document).on('click', '.more-news-btn .btn-primary', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const originalText = $button.text();
            
            // ローディング表示
            $button.text('読み込み中...').prop('disabled', true);
            
            // AJAX でニュースを読み込み
            ajaxRequest('load_latest_news', {
                limit: 4,
                offset: offset
            }, function(response) {
                if (response.success) {
                    $('.news-grid').append(response.data);
                    offset += 4;
                    
                    // 新しく追加された要素にアニメーションを適用
                    $('.news-item:hidden').fadeIn();
                } else {
                    console.error('ニュース読み込みエラー:', response);
                }
                
                // ボタンを元に戻す
                $button.text(originalText).prop('disabled', false);
            });
        });
    }

    /**
     * WordPress AJAX処理
     */
    function ajaxRequest(action, data, callback) {
        if (typeof duo_ajax === 'undefined') {
            console.error('AJAX設定が見つかりません');
            return;
        }
        
        $.ajax({
            url: duo_ajax.ajax_url,
            type: 'POST',
            data: {
                action: action,
                nonce: duo_ajax.nonce,
                ...data
            },
            success: function(response) {
                if (typeof callback === 'function') {
                    callback(response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                if (typeof callback === 'function') {
                    callback({ success: false, error: error });
                }
            }
        });
    }

    /**
     * カスタムイベントの発火
     */
    function triggerCustomEvent(eventName, data) {
        $(document).trigger(eventName, data);
    }

    /**
     * パフォーマンス最適化：スクロールイベントのスロットル
     */
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }

    /**
     * スクロール位置に応じたヘッダーの制御
     */
    function setupScrollHeader() {
        let lastScrollTop = 0;
        const $header = $('.main-header');
        
        if ($header.length === 0) return;
        
        $(window).on('scroll', throttle(function() {
            const scrollTop = $(this).scrollTop();
            
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                // 下スクロール - ヘッダーを隠す
                $header.addClass('header-hidden');
            } else {
                // 上スクロール - ヘッダーを表示
                $header.removeClass('header-hidden');
            }
            
            // 透明度の調整
            if (scrollTop > 50) {
                $header.addClass('header-scrolled');
            } else {
                $header.removeClass('header-scrolled');
            }
            
            lastScrollTop = scrollTop;
        }, 100));
    }

    /**
     * 画像の遅延読み込みエラーハンドリング
     */
    function setupImageErrorHandling() {
        $('img').on('error', function() {
            const $img = $(this);
            const fallbackSrc = duo_ajax.theme_url + '/assets/images/placeholder.jpg';
            
            if ($img.attr('src') !== fallbackSrc) {
                $img.attr('src', fallbackSrc);
                console.warn('画像読み込みエラー:', $img.attr('src'));
            }
        });
    }

    /**
     * モーダル機能（汎用）
     */
    function setupModals() {
        // モーダルを開く
        $(document).on('click', '[data-modal]', function(e) {
            e.preventDefault();
            const modalId = $(this).data('modal');
            const $modal = $('#' + modalId);
            
            if ($modal.length) {
                $modal.addClass('modal-open');
                $('body').addClass('modal-active');
            }
        });
        
        // モーダルを閉じる
        $(document).on('click', '.modal-close, .modal-overlay', function(e) {
            e.preventDefault();
            $('.modal').removeClass('modal-open');
            $('body').removeClass('modal-active');
        });
        
        // ESCキーでモーダルを閉じる
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27 && $('.modal-open').length) {
                $('.modal').removeClass('modal-open');
                $('body').removeClass('modal-active');
            }
        });
    }

    /**
     * クリップボードコピー機能
     */
    function setupClipboardCopy() {
        $(document).on('click', '[data-copy]', function(e) {
            e.preventDefault();
            const textToCopy = $(this).data('copy');
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(textToCopy).then(function() {
                    showNotification('クリップボードにコピーしました');
                });
            } else {
                // フォールバック
                const $temp = $('<input>');
                $('body').append($temp);
                $temp.val(textToCopy).select();
                document.execCommand('copy');
                $temp.remove();
                showNotification('クリップボードにコピーしました');
            }
        });
    }

    /**
     * 通知表示
     */
    function showNotification(message, type = 'success') {
        const $notification = $(`
            <div class="notification notification-${type}">
                ${message}
            </div>
        `);
        
        $('body').append($notification);
        
        setTimeout(function() {
            $notification.addClass('notification-show');
        }, 100);
        
        setTimeout(function() {
            $notification.removeClass('notification-show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 3000);
    }

    /**
     * パンくずリスト強調表示
     */
    function setupBreadcrumbHighlight() {
        $('.breadcrumb-item a').each(function() {
            if ($(this).attr('href') === window.location.pathname) {
                $(this).addClass('current-page');
            }
        });
    }

    /**
     * 外部リンクの処理
     */
    function setupExternalLinks() {
        $('a[href^="http"]:not([href*="' + window.location.hostname + '"])').each(function() {
            $(this).attr({
                'target': '_blank',
                'rel': 'noopener noreferrer'
            }).addClass('external-link');
        });
    }

    /**
     * 検索機能の強化
     */
    function setupSearchEnhancements() {
        const $searchInput = $('.search-input');
        
        if ($searchInput.length) {
            let searchTimeout;
            
            $searchInput.on('input', function() {
                clearTimeout(searchTimeout);
                const query = $(this).val().trim();
                
                if (query.length > 2) {
                    searchTimeout = setTimeout(function() {
                        performSearch(query);
                    }, 500);
                }
            });
        }
    }

    /**
     * 検索実行
     */
    function performSearch(query) {
        // AJAX検索の実装例
        ajaxRequest('search_content', {
            query: query
        }, function(response) {
            if (response.success) {
                displaySearchResults(response.data);
            }
        });
    }

    /**
     * 検索結果表示
     */
    function displaySearchResults(results) {
        const $resultsContainer = $('.search-results');
        
        if ($resultsContainer.length) {
            $resultsContainer.html(results).show();
        }
    }

    // jQuery ready時の初期化
    $(document).ready(function() {
        init();
        setupScrollHeader();
        setupImageErrorHandling();
        setupModals();
        setupClipboardCopy();
        setupBreadcrumbHighlight();
        setupExternalLinks();
        setupSearchEnhancements();
    });

    // グローバルに公開する関数
    window.DuoCommunications = {
        init: init,
        animateMVVCards: animateMVVCards,
        setupBusinessCardHover: setupBusinessCardHover,
        ajaxRequest: ajaxRequest,
        triggerCustomEvent: triggerCustomEvent,
        showNotification: showNotification,
        validateForm: validateForm
    };

    // カスタムイベント例
    $(document).on('businessCardHover', function(e, data) {
        console.log('事業部カードホバー:', data);
    });

    $(document).on('formSubmitted', function(e, data) {
        console.log('フォーム送信:', data);
    });

})(jQuery);