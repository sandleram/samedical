<div id="samed-loading-overlay" class="samed-loading-overlay" style="display:none;" aria-hidden="true">
    <div class="samed-loading-overlay__backdrop"></div>
    <div class="samed-loading-overlay__panel">
        <i class="fa fa-spinner fa-spin fa-3x"></i>
        <p class="samed-loading-overlay__message">Carregando, aguarde...</p>
    </div>
</div>

<style type="text/css">
    .samed-loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 99999;
    }

    .samed-loading-overlay__backdrop {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.72);
    }

    .samed-loading-overlay__panel {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 280px;
        margin: -70px 0 0 -140px;
        padding: 24px 16px;
        text-align: center;
        color: #fff;
        background: rgba(20, 20, 20, 0.92);
        border-radius: 6px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.45);
    }

    .samed-loading-overlay__panel p {
        margin: 14px 0 0;
        font-size: 15px;
    }
</style>

<script type="text/javascript">
    (function($) {
        window.SamedLoadingOverlay = {
            show: function(message) {
                $('#samed-loading-overlay .samed-loading-overlay__message').text(message || 'Carregando, aguarde...');
                $('#samed-loading-overlay').show().attr('aria-hidden', 'false');
            },
            hide: function() {
                $('#samed-loading-overlay').hide().attr('aria-hidden', 'true');
            }
        };
    })(jQuery);
</script>
