@extends('layouts.app')

@section('org_name', $organization->org_name)
@section('title', 'Dashboard | '.$organization->org_name)
@section('page_title', 'Dashboard')

@section('bread_crumb')
    <ol class="breadcrumb float-sm-right">
    </ol>
@endsection

@section('main_content')

<!-- Small boxes (Stat box) -->

    <div class="col-lg-6 col-12">
      <!-- small box -->
      <div class="small-box bg-light elevation-4">
        <div class="inner">
          <h3 class="text-success">50</h3>

          <p>Clients</p>
        </div>
        <div class="icon">
          <i class="ion ion-person"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-6 col-12">
      <!-- small box -->
      <div class="small-box bg-light elevation-4">
        <div class="inner">
          <h3 class="text-success">150</h3>

          <p>Users </p>
        </div>
        <div class="icon">
          <i class="ion ion-person-stalker"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-6 col-12">
      <!-- small box -->
      <div class="small-box bg-light elevation-4">
        <div class="inner">
          <h3 class="text-success">500</h3>

          <p>Disbursed Loans</p>
        </div>
        <div class="icon">
          <i class="ion ion-cash"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-6 col-12">
      <!-- small box -->
      <div class="small-box bg-light elevation-4">
        <div class="inner">
          <h3 class="text-success">53</h3>

          <p>Cleared Loans </p>
        </div>
        <div class="icon">
          <i class="ion ion-thumbsup"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  
  <!-- /.row -->
    
@endsection
