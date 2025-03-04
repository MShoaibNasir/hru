@php
    $lots = lots();
@endphp

<form method='get' action='{{route($route)}}'>
                            
                        <div class='row'>
                                <input type='hidden' value='filter' name='filter'>         
                                <div class='col-4'>
                                    <label class='label'>Select Lots</label>
                                    <select class='js-example-basic-multiple form-control' name='lot[]' id="lot" multiple="multiple">
                                        @foreach($lots as $lot)
                                        <option value='{{$lot->id}}'>{{$lot->name}}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                <div class='col-4'>
                                    <label class='label'>Select Districts</label>
                                    <select class='js-example-basic-multiple form-control' id="district" name='district[]' multiple="multiple">
                                       
                                    </select>
                                    </div>
                                <div class='col-4'>
                                    <label class='label'>Select Tehsil</label>
                                    <select class='js-example-basic-multiple form-control' id="tehsil" name='tehsil[]' multiple="multiple">
                                    </select>
                                    </div>
                                <div class='col-4'>
                                    <label class='label'>Select UC</label>
                                    <select class='js-example-basic-multiple form-control' name='uc[]' id="uc" multiple="multiple">
                                    </select>
                                    </div>
                        </div>
                                <div class='col-4' style='margin-top:4px; display:flex;'>
                                    <input type='submit' value='Filter' class='btn btn-success btn-sm'>
                                </div>
                                </form>