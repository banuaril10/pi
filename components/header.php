	<style>
	#overlay{	
		position: fixed;
		top: 0;
		z-index: 100;
		width: 100%;
		height:100%;
		display: none;
		background: rgba(0,0,0,0.6);
		}
		.cv-spinner {
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;  
		}
		.spinner {
		width: 40px;
		height: 40px;
		border: 4px #ddd solid;
		border-top: 4px #2e93e6 solid;
		border-radius: 50%;
		animation: sp-anime 0.8s infinite linear;
		}
		@keyframes sp-anime {
		100% { 
			transform: rotate(360deg); 
		}
		}
		.is-hide{
		display:none;
		}
	</style>
	
	<!--<div class="page-content">
		<section class="row">
			<div class="col-12 col-lg-12">
				<div class="row">
					<div class="col-6 col-lg-3 col-md-6">
						<div class="card">
							<div class="card-body px-3 py-4-5">
								<div class="row">
									<div class="col-md-4">
										<div class="stats-icon purple">
											<i class="iconly-boldShow"></i>
										</div>
									</div>
									<div class="col-md-8">
										<h6 class="text-muted font-semibold">New PI</h6>
										<h6 class="font-extrabold mb-0">0</h6>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-6 col-lg-3 col-md-6">
						<div class="card">
							<div class="card-body px-3 py-4-5">
								<div class="row">
									<div class="col-md-4">
										<div class="stats-icon blue">
											<i class="iconly-boldProfile"></i>
										</div>
									</div>
									<div class="col-md-8">
										<h6 class="text-muted font-semibold">Pending PI</h6>
										<h6 class="font-extrabold mb-0">0</h6>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-6 col-lg-3 col-md-6">
						<div class="card">
							<div class="card-body px-3 py-4-5">
								<div class="row">
									<div class="col-md-4">
										<div class="stats-icon green">
											<i class="iconly-boldAdd-User"></i>
										</div>
									</div>
									<div class="col-md-8">
										<h6 class="text-muted font-semibold">Verify PI</h6>
										<h6 class="font-extrabold mb-0">0</h6>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-6 col-lg-3 col-md-6">
						<div class="card">
							<div class="card-body px-3 py-4-5">
								<div class="row">
									<div class="col-md-4">
										<div class="stats-icon red">
											<i class="iconly-boldBookmark"></i>
										</div>
									</div>
									<div class="col-md-8">
										<h6 class="text-muted font-semibold">Saved Post</h6>
										<h6 class="font-extrabold mb-0">0</h6>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>-->