@component('mail::report_message')

# Hi {{$user->first_name}},


Please find your {{$product->product_name}} report attached.


You can also view this information through your Reports page:


@component('mail::button', ['url' => route('report.index')])
VIEW MY REPORTS
@endcomponent


@endcomponent
