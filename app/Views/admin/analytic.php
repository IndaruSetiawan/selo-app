<div class="head-title">
				<div class="left">
					<h1>Analytics</h1>
					<ul class="breadcrumb">
						<li><a href="#">Dashboard</a></li>
						<li><i class="bx bx-chevron-right"></i></li>
						<li><a class="active" href="#">Analytics</a></li>
					</ul>
				</div>
				<select class="chart-select" id="year-select" aria-label="Pilih tahun"></select>
			</div>

			<!-- MAIN CHART: selebar kartu .order -->
			<div class="table-data">
				<div class="order chart-card">
					<div class="card-head">
						<div>
							<h3>Sales Overview</h3>
							<p class="card-sub" id="sales-total"></p>
						</div>
					</div>
					<div id="chart-sales"></div>
				</div>
			</div>

			<!-- Revenue vs Cost + Pie -->
			<div class="table-data">
				<div class="order chart-card">
					<div class="card-head">
						<div>
							<h3>Revenue vs Cost</h3>
							<p class="card-sub">Penjualan, modal, dan laba per bulan</p>
						</div>
					</div>
					<div id="chart-revenue"></div>
				</div>

				<div class="chart-side chart-card">
					<div class="card-head">
						<div>
							<h3>Sales by Menu</h3>
							<p class="card-sub">Porsi penjualan per menu</p>
						</div>
						<select class="chart-select" id="month-select" aria-label="Pilih bulan"></select>
					</div>
					<div id="chart-pie"></div>
				</div>
			</div>

			<!-- Menu terlaris per bulan -->
			<div class="table-data">
				<div class="order">
					<div class="card-head">
						<div>
							<h3>Best-Selling Menu per Month</h3>
							<p class="card-sub">Menu dengan jumlah terjual terbanyak di setiap bulan</p>
						</div>
					</div>
					<table class="best-table">
						<thead>
							<tr>
								<th>Month</th>
								<th>Menu</th>
								<th>Category</th>
								<th class="num">Sold</th>
								<th class="num">Revenue</th>
							</tr>
						</thead>
						<tbody id="best-body"></tbody>
					</table>
				</div>
			</div>