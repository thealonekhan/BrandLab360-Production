
<div class="nav-tabs-boxed">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#location" role="tab" aria-controls="home" aria-selected="true">Location</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#product" role="tab" aria-controls="profile" aria-selected="false">Product</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#video" role="tab" aria-controls="messages" aria-selected="false">Video</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="location" role="tabpanel">

            <table class="table table-responsive-sm table-bordered">
                <thead>
                <tr>
                    <th>Event Label</th>
                    <th>Total Events</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($eventData->rows))
                @foreach($eventData->rows as $location)
                @if(!empty($location[0]) && strtolower($location[0]) == 'location')
                <tr>
                    <td>{{$location[1]}}</td>
                    <td>{{$location[2]}}</td>
                </tr>
                @endif
                @endforeach
                @endif
                </tbody>
            </table>
            
        </div>
        <div class="tab-pane" id="product" role="tabpanel">
            <table class="table table-responsive-sm table-bordered">
                <thead>
                <tr>
                    <th>Event Label</th>
                    <th>Total Events</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($eventData->rows))
                @foreach($eventData->rows as $product)
                @if(!empty($product[0]) && strtolower($product[0]) == 'product')
                <tr>
                    <td>{{$product[1]}}</td>
                    <td>{{$product[2]}}</td>
                </tr>
                @endif
                @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="video" role="tabpanel">
            <table class="table table-responsive-sm table-bordered">
                <thead>
                <tr>
                    <th>Event Label</th>
                    <th>Total Events</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($eventData->rows))
                @foreach($eventData->rows as $video)
                @if(!empty($video[0]) && strtolower($video[0]) == 'video')
                <tr>
                    <td>{{$video[1]}}</td>
                    <td>{{$video[2]}}</td>
                </tr>
                @endif
                @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>