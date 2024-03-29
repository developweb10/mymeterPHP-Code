@if( $vars['export'] === '' )
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="com"></div>
			<div class="panel panel-default">
				<div class="panel-heading panel-heading-lg">
					<a href="{{ URL::to('/home/report') }}" class="btn btn-default btn-sm">Overview</a>
					<a href="{{ URL::to('/home/report_by_groups') }}" class="btn btn-default btn-sm">Report By Groups</a>
					<a href="{{ URL::to('/home/report_by_meters') }}" class="btn btn-default btn-sm selected">Report By Meters</a>
				</div>
				<div class="panel-body">
					@if ( $vars['tab'] === 'report_by_groups' && count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					@if( $vars['tab'] === 'report_by_groups' && Session::has('success'))
						<div class="alert alert-success">
							<strong>Success!</strong> {{ Session::get('success') }}
						</div>
					@endif
						
					<div class="text-center">
						<form role="form" class="form-inline filter-form" id="filter-form">
							<input type="hidden" name="export" id="export" value="">
							<fieldset>
								<span class="to-text"><b>Filter Results</b></span>
								<select class="form-control" name="group_id">
									<option value="">Select Group (Default:All)</option>
									@foreach( $mylots as $group )
										<option value="{{ $group['id'] }}"  @if( $group['id'] == $vars['group_id'] ) selected="selected" @endif     >{{ $group['lot_name'] }}</option>
									@endforeach
								</select>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="text" class="form-control datepicker1" name="start_date" value="{{ $vars['start_date'] }}" placeholder="Start Date" />
								<input type="text" class="form-control datepicker1" name="end_date" value="{{ $vars['end_date'] }}" placeholder="End Date" />
								<input type="submit" class="btn btn-default"  value="Submit" />
								<input type="reset" class="btn btn-default" value="Clear">
							</fieldset>
						</form>
					</div>
					<br />
@else

	<h2>Report By Meters ( @if( $vars['start_date'] !== '' ) From: {{ $vars['start_date'] }} @endif  @if( $vars['end_date'] !== '' ) To: {{ $vars['end_date'] }} @endif  ) </h2>
	
	@if( $vars['export'] === 'PDF' )
		<html>
		<head>
		</head>
		<body style="padding:0px; margin:0px;border: 3px solid gray;">
	@endif
	
@endif		
					<table id="datatable" class="display" cellspacing="0" width="100%" @if( $vars['export'] !== '' ) border="1" @endif >
						<thead>
							<tr>
								<th class="text-center">Meter #</th>
								<th class="text-center">Group Name</th>
								<th class="text-center">No. of Transactions</th>
								<th class="text-center">Total Hours</th>
								<?php /*?><th class="text-center">Total Revenue($)</th><?php */?>
								<th class="text-center">Revenue($)</th>
							</tr>
						</thead>
						<tbody>
							<?php $totals = array("revenue"=>0.00,"transactions"=>0.00,"hours"=>0,"net_revenue"=>0.00); ?>
							@foreach( $vars['meter_details'] as $meter )
								<tr>
									<td class="text-center">{{ $meter->meter_id }}</td>
									<td class="text-center">{{ $meter->lot_name }}</td>
									<td class="text-center">{{ $meter->transactions }}</td>
									<td class="text-center">{{ $meter->total_hours }}</td>
									<?php /*?><td class="text-center">{{ number_format($meter->trans_amount,2) }}</td><?php */?>
									<?php $net_revenue =  ( $meter->trans_amount * 80 )/100;  ?>
									<td class="text-center">{{ number_format($net_revenue,2) }}</td>
								</tr>
								<?php 
									$totals["revenue"] += $meter->trans_amount;  $totals["transactions"] += $meter->transactions; $totals["hours"] += $meter->total_hours; $totals["net_revenue"] += $net_revenue; 
								?>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td class="text-center">TOTALS</td>
								<td class="text-center"></td>
								<td class="text-center">{{ $totals["transactions"] }}</td>
								<td class="text-center">{{ $totals["hours"] }}</td>
								<?php /*?><td class="text-center">{{ number_format($totals["revenue"],2) }}</td><?php */?>
								<td class="text-center">{{ number_format($totals["net_revenue"],2) }}</td>
							</tr>
						</tfoot>
					</table>
@if( $vars['export'] === '' )	
					@include('includes.export-buttons')
				</div>
				
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

<script>var datatable_searching = true;</script>
@else
		@if( $vars['export'] === 'PDF' )
			</body>
			</html>
		@endif
@endif

