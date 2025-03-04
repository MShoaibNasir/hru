<div class="message-item" id="m16">
						<div class="message-inner">
							<div class="message-head clearfix">
								<div class="avatar float-start">
								    <a href="#"><img src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png"></a>
								    <h5 class="handle">{{ $followup->assign_by->name ?? '' }}</h5>
								</div>
								<span class="float-end">Last Status: <span class="badge bg-primary">{{ $followup->currentstatus }}</span></span><br clear="all"/>
								<span class="float-end">Take Action: <span class="badge bg-primary">{{ $followup->status }}</span></span><br clear="all"/>
								<div class="user-detail">
									<div class="post-meta">
										<div class="asker-meta">
											<span class="qa-message-what"></span>
											<span class="qa-message-when">
												<span class="qa-message-when-data"><span class="badge bg-danger">{{ Carbon\Carbon::parse($followup->created_at)->format('D d M Y (g:i A)') }}</span></span>
											</span>
											<span class="qa-message-who">
												<span class="qa-message-who-pad">by </span>
												<span class="qa-message-who-data"><span class="badge bg-info">{{ $followup->assign_by->name ?? '' }}</span></span>
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="qa-message-content">
								{{ $followup->remark }}
							</div>
					</div></div>


{{--
<!-- TIMELINE ITEM -->
<div class="timeline-item">
	<div class="timeline-badge">
		<div class="timeline-icon">
			<i class="icon-user-following font-green-haze"></i>
		</div>
	</div>
	<div class="timeline-body">
		<div class="timeline-body-arrow"> </div>
		<div class="timeline-body-head">
			<div class="timeline-body-head-caption">
				<span class="timeline-body-title font-blue-madison">{{ $followup->assign_by->name }}</span>
				<span class="timeline-body-time font-green"><span class="label label-danger">{{ Carbon\Carbon::parse($followup->created_at)->format('D d M Y (g:i A)') }}</span></span>
			</div>
			<span class="pull-right">Last Status: <span class="label label-primary">{{ $followup->status }}</span></span>
		</div>
		<div class="timeline-body-content">
			<span class="timeline-body-title font-blue-madison">Remarks:</span>
			<span class="font-black"> {{ $followup->remark }}</span>
		</div>
	</div>
</div>
<!-- END TIMELINE ITEM -->
--}}






