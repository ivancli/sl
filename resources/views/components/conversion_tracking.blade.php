
@if(auth()->check() && auth()->user()->set_conversion != 'y')
    @if(!is_null(auth()->user()->subscription) && auth()->user()->subscription->location == 'us')
        <script type="text/javascript">
            var capterra_vkey = '4596f7f001c14d80b9df45fb40bed681',
                capterra_vid = '2094080',
                capterra_prefix = (('https:' == document.location.protocol) ? 'https://ct.capterra.com' : 'http://ct.capterra.com');
            (function () {
                var ct = document.createElement('script');
                ct.type = 'text/javascript';
                ct.async = true;
                ct.src = capterra_prefix + '/capterra_tracker.js?vid=' + capterra_vid + '&vkey=' + capterra_vkey;
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ct, s);
            })();
        </script>
    @else
        <!-- Google Code for Sign Up Conversion Page -->
        <script type="text/javascript">
            /* <![CDATA[ */
            var google_conversion_id = 855050554;
            var google_conversion_language = "en";
            var google_conversion_format = "3";
            var google_conversion_color = "ffffff";
            var google_conversion_label = "MSeiCNX9rXAQupLclwM";
            var google_conversion_value = 0.00;
            var google_conversion_currency = "AUD";
            var google_remarketing_only = false;
            /* ]]> */
        </script>
        <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
        <noscript>
            <div style="display:inline;">
                <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/855050554/?value=0.00&amp;currency_code=AUD&amp;label=MSeiCNX9rXAQupLclwM&amp;guid=ON&amp;script=0"/>
            </div>
        </noscript>
    @endif
    <?php auth()->user()->setConversionTracked(); ?>
@endif