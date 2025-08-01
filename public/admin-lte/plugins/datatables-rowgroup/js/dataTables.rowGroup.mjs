/*! RowGroup 1.5.2
 * © SpryMedia Ltd - datatables.net/license
 */

import jQuery from 'jquery';
import DataTable from 'datatables.net';

// Allow reassignment of the $ variable
let $ = jQuery;


/**
 * @summary     RowGroup
 * @description RowGrouping for DataTables
 * @version     1.5.2
 * @author      SpryMedia Ltd (www.sprymedia.co.uk)
 * @contact     datatables.net
 * @copyright   SpryMedia Ltd.
 *
 * This source file is free software, available under the following license:
 *   MIT license - http://datatables.net/license/mit
 *
 * This source file is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.
 *
 * For details please refer to: http://www.datatables.net
 */

var RowGroup = function (dt, opts) {
	// Sanity check that we are using DataTables 1.10 or newer
	if (!DataTable.versionCheck || !DataTable.versionCheck('1.11')) {
		throw 'RowGroup requires DataTables 1.11 or newer';
	}

	// User and defaults configuration object
	this.c = $.extend(true, {}, DataTable.defaults.rowGroup, RowGroup.defaults, opts);

	// Internal settings
	this.s = {
		dt: new DataTable.Api(dt)
	};

	// DOM items
	this.dom = {};

	// Check if row grouping has already been initialised on this table
	var settings = this.s.dt.settings()[0];
	var existing = settings.rowGroup;
	if (existing) {
		return existing;
	}

	settings.rowGroup = this;
	this._constructor();
};

$.extend(RowGroup.prototype, {
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * API methods for DataTables API interface
	 */

	/**
	 * Get/set the grouping data source - need to call draw after this is
	 * executed as a setter
	 * @returns string~RowGroup
	 */
	dataSrc: function (val) {
		if (val === undefined) {
			return this.c.dataSrc;
		}

		var dt = this.s.dt;

		this.c.dataSrc = val;

		$(dt.table().node()).triggerHandler('rowgroup-datasrc.dt', [dt, val]);

		return this;
	},

	/**
	 * Disable - need to call draw after this is executed
	 * @returns RowGroup
	 */
	disable: function () {
		this.c.enable = false;
		return this;
	},

	/**
	 * Enable - need to call draw after this is executed
	 * @returns RowGroup
	 */
	enable: function (flag) {
		if (flag === false) {
			return this.disable();
		}

		this.c.enable = true;
		return this;
	},

	/**
	 * Get enabled flag
	 * @returns boolean
	 */
	enabled: function () {
		return this.c.enable;
	},

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Constructor
	 */
	_constructor: function () {
		var that = this;
		var dt = this.s.dt;
		var hostSettings = dt.settings()[0];
		var scroller = $('div.dt-scroll-body', dt.table().container());

		dt.on('draw.dtrg', function (e, s) {
			if (that.c.enable && hostSettings === s) {
				that._draw();

				// Restore scrolling position if set and paging wasn't reset
				if (scrollTop && scroller.scrollTop()) {
					scroller.scrollTop(scrollTop);
					scrollTop = null;
				}
			}
		});

		dt.on('column-visibility.dt.dtrg responsive-resize.dt.dtrg', function () {
			that._adjustColspan();
		});

		dt.on('destroy', function () {
			dt.off('.dtrg');
		});

		// When scrolling is enabled, when adding grouping rows above the scrolling view
		// port, the browser (both FF and Chrome) will put the element in and adjust the
		// scrollTop so that it doesn't move the current viewport. This isn't what we
		// want since prior to the draw the grouping elements were in place, but they then
		// are removed and reinserted. So we need to shift the scrollTop back to what it
		// was!
		var scrollTop = null;

		if (scroller.length) {
			dt.on('preDraw', function () {
				scrollTop = scroller.scrollTop();
			});
		}
	},

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Private methods
	 */

	/**
	 * Adjust column span when column visibility changes
	 * @private
	 */
	_adjustColspan: function () {
		$('tr.' + this.c.className, this.s.dt.table().body())
			.find('th:visible, td:visible')
			.attr('colspan', this._colspan());
	},

	/**
	 * Get the number of columns that a grouping row should span
	 * @private
	 */
	_colspan: function () {
		return this.s.dt
			.columns()
			.visible()
			.reduce(function (a, b) {
				return a + b;
			}, 0);
	},

	/**
	 * Update function that is called whenever we need to draw the grouping rows.
	 * This is basically a bootstrap for the self iterative _group and _groupDisplay
	 * methods
	 * @private
	 */
	_draw: function () {
		var dt = this.s.dt;
		var groupedRows = this._group(0, dt.rows({ page: 'current' }).indexes());

		this._groupDisplay(0, groupedRows);
	},

	/**
	 * Get the grouping information from a data set (index) of rows
	 * @param {number} level Nesting level
	 * @param {DataTables.Api} rows API of the rows to consider for this group
	 * @returns {object[]} Nested grouping information - it is structured like this:
	 *	{
	 *		dataPoint: 'Edinburgh',
	 *		rows: [ 1,2,3,4,5,6,7 ],
	 *		children: [ {
	 *			dataPoint: 'developer'
	 *			rows: [ 1, 2, 3 ]
	 *		},
	 *		{
	 *			dataPoint: 'support',
	 *			rows: [ 4, 5, 6, 7 ]
	 *		} ]
	 *	}
	 * @private
	 */
	_group: function (level, rows) {
		var fns = Array.isArray(this.c.dataSrc) ? this.c.dataSrc : [this.c.dataSrc];
		var fn = DataTable.util.get(fns[level]);
		var dt = this.s.dt;
		var group, last;
		var i, ien;
		var data = [];
		var that = this;

		for (i = 0, ien = rows.length; i < ien; i++) {
			var rowIndex = rows[i];
			var rowData = dt.row(rowIndex).data();

			group = fn(rowData, level);

			if (group === null || group === undefined) {
				group = that.c.emptyDataGroup;
			}

			if (last === undefined || group !== last) {
				data.push({
					dataPoint: group,
					rows: []
				});

				last = group;
			}

			data[data.length - 1].rows.push(rowIndex);
		}

		if (fns[level + 1] !== undefined) {
			for (i = 0, ien = data.length; i < ien; i++) {
				data[i].children = this._group(level + 1, data[i].rows);
			}
		}

		return data;
	},

	/**
	 * Row group display - insert the rows into the document
	 * @param {number} level Nesting level
	 * @param {object[]} groups Takes the nested array from `_group`
	 * @private
	 */
	_groupDisplay: function (level, groups) {
		var dt = this.s.dt;
		var display;

		for (var i = 0, ien = groups.length; i < ien; i++) {
			var group = groups[i];
			var groupName = group.dataPoint;
			var row;
			var rows = group.rows;

			if (this.c.startRender) {
				display = this.c.startRender.call(this, dt.rows(rows), groupName, level);
				row = this._rowWrap(display, this.c.startClassName, level);

				if (row) {
					row.insertBefore(dt.row(rows[0]).node());
				}
			}

			if (this.c.endRender) {
				display = this.c.endRender.call(this, dt.rows(rows), groupName, level);
				row = this._rowWrap(display, this.c.endClassName, level);

				if (row) {
					row.insertAfter(dt.row(rows[rows.length - 1]).node());
				}
			}

			if (group.children) {
				this._groupDisplay(level + 1, group.children);
			}
		}
	},

	/**
	 * Take a rendered value from an end user and make it suitable for display
	 * as a row, by wrapping it in a row, or detecting that it is a row.
	 * @param {node|jQuery|string} display Display value
	 * @param {string} className Class to add to the row
	 * @param {array} group
	 * @param {number} group level
	 * @private
	 */
	_rowWrap: function (display, className, level) {
		var row;

		if (display === null || display === '') {
			display = this.c.emptyDataGroup;
		}

		if (display === undefined || display === null) {
			return null;
		}

		if (
			typeof display === 'object' &&
			display.nodeName &&
			display.nodeName.toLowerCase() === 'tr'
		) {
			row = $(display);
		}
		else if (
			display instanceof $ &&
			display.length &&
			display[0].nodeName.toLowerCase() === 'tr'
		) {
			row = display;
		}
		else {
			row = $('<tr/>').append(
				$('<th/>').attr('colspan', this._colspan()).attr('scope', 'row').append(display)
			);
		}

		return row
			.addClass(this.c.className)
			.addClass(className)
			.addClass('dtrg-level-' + level);
	}
});

/**
 * RowGroup default settings for initialisation
 *
 * @namespace
 * @name RowGroup.defaults
 * @static
 */
RowGroup.defaults = {
	/**
	 * Class to apply to grouping rows - applied to both the start and
	 * end grouping rows.
	 * @type string
	 */
	className: 'dtrg-group',

	/**
	 * Data property from which to read the grouping information
	 * @type string|integer|array
	 */
	dataSrc: 0,

	/**
	 * Text to show if no data is found for a group
	 * @type string
	 */
	emptyDataGroup: 'No group',

	/**
	 * Initial enablement state
	 * @boolean
	 */
	enable: true,

	/**
	 * Class name to give to the end grouping row
	 * @type string
	 */
	endClassName: 'dtrg-end',

	/**
	 * End grouping label function
	 * @function
	 */
	endRender: null,

	/**
	 * Class name to give to the start grouping row
	 * @type string
	 */
	startClassName: 'dtrg-start',

	/**
	 * Start grouping label function
	 * @function
	 */
	startRender: function (rows, group) {
		return group;
	}
};

RowGroup.version = '1.5.2';

$.fn.dataTable.RowGroup = RowGroup;
$.fn.DataTable.RowGroup = RowGroup;

DataTable.Api.register('rowGroup()', function () {
	return this;
});

DataTable.Api.register('rowGroup().disable()', function () {
	return this.iterator('table', function (ctx) {
		if (ctx.rowGroup) {
			ctx.rowGroup.enable(false);
		}
	});
});

DataTable.Api.register('rowGroup().enable()', function (opts) {
	return this.iterator('table', function (ctx) {
		if (ctx.rowGroup) {
			ctx.rowGroup.enable(opts === undefined ? true : opts);
		}
	});
});

DataTable.Api.register('rowGroup().enabled()', function () {
	var ctx = this.context;

	return ctx.length && ctx[0].rowGroup ? ctx[0].rowGroup.enabled() : false;
});

DataTable.Api.register('rowGroup().dataSrc()', function (val) {
	if (val === undefined) {
		return this.context[0].rowGroup.dataSrc();
	}

	return this.iterator('table', function (ctx) {
		if (ctx.rowGroup) {
			ctx.rowGroup.dataSrc(val);
		}
	});
});

// Attach a listener to the document which listens for DataTables initialisation
// events so we can automatically initialise
$(document).on('preInit.dt.dtrg', function (e, settings, json) {
	if (e.namespace !== 'dt') {
		return;
	}

	var init = settings.oInit.rowGroup;
	var defaults = DataTable.defaults.rowGroup;

	if (init || defaults) {
		var opts = $.extend({}, defaults, init);

		if (init !== false) {
			new RowGroup(settings, opts);
		}
	}
});


export default DataTable;
