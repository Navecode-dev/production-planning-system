 @extends('layouts.admin')
 @section('title', 'Dashboard')

 @section('content')
 <div class="row">
     <div class="col-lg-3 col-6">
         <!-- small box -->
         <div class="small-box bg-info">
             <div class="inner">
                 <h3>{{ $totalProducts }}</h3>
                 <p>Total Produk</p>
             </div>
             <div class="icon">
                 <i class="ion ion-bag"></i>
             </div>
             <a href="{{ route('products.index') }}" class="small-box-footer">Info lebih lanjut <i class="fas fa-arrow-circle-right"></i></a>
         </div>
     </div>
     <!-- ./col -->
     <div class="col-lg-3 col-6">
         <!-- small box -->
         <div class="small-box bg-success">
             <div class="inner">
                 <h3>{{ $totalStores }}</h3>
                 <p>Total Toko</p>
             </div>
             <div class="icon">
                 <i class="ion ion-stats-bars"></i>
             </div>
             <a href="{{ route('stores.index') }}" class="small-box-footer">Info lebih lanjut <i class="fas fa-arrow-circle-right"></i></a>
         </div>
     </div>
     <!-- ./col -->
     <div class="col-lg-3 col-6">
         <!-- small box -->
         <div class="small-box bg-warning">
             <div class="inner">
                 <h3>{{ $totalRawMaterials }}</h3>
                 <p>Total Bahan Baku</p>
             </div>
             <div class="icon">
                 <i class="ion ion-cube"></i>
             </div>
             <a href="{{ route('raw-materials.index') }}" class="small-box-footer">Info lebih lanjut <i class="fas fa-arrow-circle-right"></i></a>
         </div>
     </div>
     <!-- ./col -->
     <div class="col-lg-3 col-6">
         <!-- small box -->
         <div class="small-box bg-danger">
             <div class="inner">
                 <h3>{{ $totalProductCriteria + $totalStoreCriteria }}</h3>
                 <p>Total Kriteria</p>
             </div>
             <div class="icon">
                 <i class="ion ion-pie-graph"></i>
             </div>
             <a href="{{ route('criteria.index') }}" class="small-box-footer">Info lebih lanjut <i class="fas fa-arrow-circle-right"></i></a>
         </div>
     </div>
     <!-- ./col -->
 </div>

 <div class="row">
     <div class="col-md-6">
         <div class="card">
             <div class="card-header">
                 <h3 class="card-title">Rincian Kriteria</h3>
             </div>
             <div class="card-body p-0">
                 <ul class="list-group list-group-flush">
                     <li class="list-group-item d-flex justify-content-between align-items-center">
                         Kriteria Produk
                         <span class="badge bg-primary badge-pill">{{ $totalProductCriteria }}</span>
                     </li>
                     <li class="list-group-item d-flex justify-content-between align-items-center">
                         Kriteria Toko
                         <span class="badge bg-primary badge-pill">{{ $totalStoreCriteria }}</span>
                     </li>
                 </ul>
             </div>
         </div>
     </div>
 </div>
 @endsection

 @push('styles')
 <!-- Ionicons -->
 <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
 @endpush