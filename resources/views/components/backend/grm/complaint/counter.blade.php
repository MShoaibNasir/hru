<div class="col-sm-6 col-xl-4">
    <a href="{{ $url }}">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-edit fa-2x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2 text-dark">{{ $title }}</p>
                        <h6 class="mb-0">{{ $data->count() ?? 0 }}</h6>
                    </div>
                </div>
                </a>
            </div>