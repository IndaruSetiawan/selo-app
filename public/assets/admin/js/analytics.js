(function () {
	'use strict';

	var MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	var MONTHS_FULL = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

	var SRC = window.ANALYTICS;
	var MENUS = SRC.menus;
	var DATA = SRC.data;
	var YEARS =SRC.years;
	var LAST_MONTH = SRC.lastMonth;

	var state = { year: YEARS[YEARS.length - 1], month: 0 };
	state.month = LAST_MONTH[state.year];

	/*  Helper  */
	function $(id) { return document.getElementById(id); }

	function esc(v) {
	return String(v).replace(/[&<>"']/g, function (c) {
		return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
	});
}

	function rupiah(n) {
		return 'Rp ' + Math.round(n).toLocaleString('id-ID');
	}

	function juta(v) {
		return (v / 1000000).toFixed(0) + ' jt';
	}

	function cssVar(name, fallback) {
		var v = getComputedStyle(document.body).getPropertyValue(name).trim();
		return v || fallback;
	}

	function padded(values) {
		var out = values.slice();
		while (out.length < 12) out.push(null);
		return out;
	}

	function themeOptions() {
		return {
			chart: { foreColor: cssVar('--dark', '#342E37') },
			grid: { borderColor: cssVar('--grey', '#EEEEEE') },
			tooltip: { theme: document.body.classList.contains('dark') ? 'dark' : 'light' }
		};
	}

	var BLUE = cssVar('--blue', '#3C91E6');
	var ORANGE = cssVar('--orange', '#FD7238');
	var YELLOW = cssVar('--yellow', '#FFCE26');
	var GREEN = '#00A86B';
	var PURPLE = '#8E6FD8';
	var RED = cssVar('--red', '#DB504A');
	var PALETTE = [BLUE, ORANGE, YELLOW, GREEN, PURPLE, RED, '#2BB3C0', '#E85D9A', '#7FB800', '#8D6E63', '#5C6BC0', '#26A69A'];

	/*  Chart  */
	var theme = themeOptions();

	var salesChart = new ApexCharts($('chart-sales'), {
		chart: {
			type: 'area',
			height: 380,
			toolbar: { show: false },
			zoom: { enabled: false },
			fontFamily: 'Poppins, sans-serif',
			foreColor: theme.chart.foreColor
		},
		series: [{ name: 'Sales', data: [] }],
		colors: [BLUE],
		stroke: { curve: 'smooth', width: 3 },
		fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02 } },
		dataLabels: { enabled: false },
		xaxis: { categories: MONTHS },
		yaxis: { labels: { formatter: juta } },
		grid: { borderColor: theme.grid.borderColor, strokeDashArray: 4 },
		tooltip: { theme: theme.tooltip.theme, y: { formatter: rupiah } }
	});

	var revenueChart = new ApexCharts($('chart-revenue'), {
		chart: {
			type: 'bar',
			height: 340,
			toolbar: { show: false },
			fontFamily: 'Poppins, sans-serif',
			foreColor: theme.chart.foreColor
		},
		series: [],
		colors: [BLUE, ORANGE, GREEN],
		plotOptions: { bar: { columnWidth: '65%', borderRadius: 4 } },
		dataLabels: { enabled: false },
		stroke: { show: true, width: 2, colors: ['transparent'] },
		xaxis: { categories: MONTHS },
		yaxis: { labels: { formatter: juta } },
		legend: { position: 'top', horizontalAlign: 'right' },
		grid: { borderColor: theme.grid.borderColor, strokeDashArray: 4 },
		tooltip: { theme: theme.tooltip.theme, y: { formatter: rupiah } }
	});

	var pieChart = new ApexCharts($('chart-pie'), {
		chart: {
			type: 'pie',
			height: 340,
			fontFamily: 'Poppins, sans-serif',
			foreColor: theme.chart.foreColor
		},
		series: [],
		labels: MENUS.map(function (m) { return m.name.replace(/[<>]/g, ''); }),
		colors: PALETTE,
		stroke: { colors: [cssVar('--light', '#FFFFFF')] },
		legend: { position: 'bottom' },
		dataLabels: { formatter: function (v) { return v.toFixed(0) + '%'; } },
		tooltip: { theme: theme.tooltip.theme, y: { formatter: rupiah } }
	});

	salesChart.render();
	revenueChart.render();
	pieChart.render();

	function fillYearSelect() {
		$('year-select').innerHTML = YEARS.map(function (y) {
			return '<option value="' + y + '"' + (y === state.year ? ' selected' : '') + '>' + y + '</option>';
		}).join('');
	}

	function fillMonthSelect() {
		var last = LAST_MONTH[state.year];
		if (state.month > last) state.month = last;
		var html = '';
		for (var m = 0; m <= last; m++) {
			html += '<option value="' + m + '"' + (m === state.month ? ' selected' : '') + '>' + MONTHS_FULL[m] + '</option>';
		}
		$('month-select').innerHTML = html;
	}

	/*  Render  */
	function renderPie() {
	if (!MENUS.length) return;
	pieChart.updateSeries(DATA[state.year][state.month].rev);
}

function renderTable() {
	var rows = DATA[state.year];
	var html = '';
	for (var m = rows.length - 1; m >= 0; m--) {
		var r = rows[m];
		var best = 0;
		r.qty.forEach(function (q, i) { if (q > r.qty[best]) best = i; });
		var label = MONTHS_FULL[m] + ' ' + state.year;

		if (!MENUS.length || r.qty[best] === 0) {
			html += '<tr><td>' + label + '</td><td colspan="4">No sales</td></tr>';
			continue;
		}
		var menu = MENUS[best];
		html += '<tr>' +
			'<td>' + label + '</td>' +
			'<td>' + esc(menu.name) + '</td>' +
			'<td><span class="pill ' + esc(menu.category.toLowerCase()) + '">' + esc(menu.category) + '</span></td>' +
			'<td class="num">' + r.qty[best].toLocaleString('id-ID') + ' porsi</td>' +
			'<td class="num">' + rupiah(r.rev[best]) + '</td>' +
			'</tr>';
	}
	$('best-body').innerHTML = html;
}

	function render() {
		var rows = DATA[state.year];
		var sales = rows.map(function (r) { return r.sales; });
		var cost = rows.map(function (r) { return r.cost; });
		var profit = rows.map(function (r) { return r.sales - r.cost; });

		salesChart.updateSeries([{ name: 'Sales', data: padded(sales) }]);
		revenueChart.updateSeries([
			{ name: 'Sales', data: padded(sales) },
			{ name: 'Cost', data: padded(cost) },
			{ name: 'Profit', data: padded(profit) }
		]);

		var total = sales.reduce(function (a, b) { return a + b; }, 0);
		$('sales-total').textContent = 'Total penjualan ' + state.year + ': ' + rupiah(total);

		fillMonthSelect();
		renderPie();
		renderTable();
	}

	$('year-select').addEventListener('change', function () {
		state.year = parseInt(this.value, 10);
		render();
	});

	$('month-select').addEventListener('change', function () {
		state.month = parseInt(this.value, 10);
		renderPie();
	});

	/* Dark mode */
	var modeSwitch = $('switch-mode');
	if (modeSwitch) {
		modeSwitch.addEventListener('change', function () {
			setTimeout(function () {
				var t = themeOptions();
				salesChart.updateOptions({ chart: t.chart, grid: t.grid, tooltip: t.tooltip });
				revenueChart.updateOptions({ chart: t.chart, grid: t.grid, tooltip: t.tooltip });
				pieChart.updateOptions({
					chart: t.chart,
					tooltip: t.tooltip,
					stroke: { colors: [cssVar('--light', '#FFFFFF')] }
				});
			}, 50);
		});
	}

	fillYearSelect();
	render();
})();