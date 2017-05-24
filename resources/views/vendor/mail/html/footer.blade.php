<tr>
    <td>
        <table class="footer" width="570" cellpadding="0" cellspacing="0">
            <tr>
                <td class="content-cell">
                    <p>
                        <a href="//spotlite.com.au">
                            <img src="{{asset('images/youtube.png')}}" alt="" width="26" height="26">
                        </a>
                        <a href="//spotlite.com.au">
                            <img src="{{asset('images/globe.png')}}" alt="" width="26" height="26">
                        </a>
                    </p>
                    {{ Illuminate\Mail\Markdown::parse($slot) }}
                </td>
            </tr>
        </table>
    </td>
</tr>
