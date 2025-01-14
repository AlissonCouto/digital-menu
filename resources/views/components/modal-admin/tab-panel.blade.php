<ul class="nav nav-tabs tabs-resume-orders" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link" id="client-tab" data-bs-toggle="tab" href="#client" role="tab" aria-controls="client" aria-selected="false">Cliente</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" id="address-tab" data-bs-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="true">Endereço</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="historic-tab" data-bs-toggle="tab" href="#historic" role="tab" aria-controls="historic" aria-selected="false">Histórico</a>
    </li>
</ul>
<div class="tab-content tabs-data-clients" id="myTabContent">
    <div class="tab-pane fade" id="client" role="tabpanel" aria-labelledby="client-tab">
        @include('components.modal-admin.tabs.client')
    </div>
    <div class="tab-pane fade show active" id="address" role="tabpanel" aria-labelledby="address-tab">
        @include('components.modal-admin.tabs.address')
    </div>
    <div class="tab-pane fade" id="historic" role="tabpanel" aria-labelledby="historic-tab">
        @include('components.modal-admin.tabs.historic')
    </div>
</div>
