/**
 * City College of Education, Mararaba — Frontend Interactive Scripts
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		// 1. Live Course Catalog Filter on Courses Page
		var courseSearch = document.getElementById('ccoe-course-search');
		if (courseSearch) {
			courseSearch.addEventListener('input', function () {
				var query = courseSearch.value.toLowerCase().trim();
				var tables = document.querySelectorAll('.ccoe-searchable-table tbody tr');
				tables.forEach(function (row) {
					var text = row.textContent.toLowerCase();
					row.style.display = text.indexOf(query) !== -1 ? '' : 'none';
				});
			});
		}

		// 2. Dynamic State -> LGA Datalist on Provisional Admission Form
		var stateSelect = document.getElementById('state_of_origin');
		var lgaDatalist = document.getElementById('ccoe-lga-datalist');
		if (stateSelect && lgaDatalist && window.ccoeData && window.ccoeData.statesLgas) {
			stateSelect.addEventListener('change', function () {
				var selectedState = stateSelect.value;
				lgaDatalist.innerHTML = '';
				var lgas = window.ccoeData.statesLgas[selectedState] || [];
				lgas.forEach(function (lga) {
					var opt = document.createElement('option');
					opt.value = lga;
					lgaDatalist.appendChild(opt);
				});
			});
		}

		// 3. Dynamic Programme Level -> Preferred Course Filter on Provisional Admission Form
		var progSelect = document.getElementById('programme_interest');
		var courseSelect = document.getElementById('preferred_course');
		if (progSelect && courseSelect && window.ccoeData && window.ccoeData.coursesByProgram) {
			progSelect.addEventListener('change', function () {
				var selectedProg = progSelect.value;
				var map = window.ccoeData.coursesByProgram;
				if (!selectedProg || !map[selectedProg]) {
					return;
				}
				courseSelect.innerHTML = '<option value="">— Select Preferred Course of Study —</option>';
				map[selectedProg].forEach(function (courseName) {
					var opt = document.createElement('option');
					opt.value = courseName;
					opt.textContent = courseName;
					courseSelect.appendChild(opt);
				});
			});
		}

		// 4. Client-Side File Size & Format Validation for Passport & Credentials
		var passportInput = document.getElementById('passport_photo');
		if (passportInput) {
			passportInput.addEventListener('change', function () {
				var file = passportInput.files && passportInput.files[0];
				if (!file) return;
				var maxBytes = 2 * 1024 * 1024;
				if (file.size > maxBytes) {
					alert('Passport photograph must not exceed 2 MB. Please choose a smaller image.');
					passportInput.value = '';
				}
			});
		}

		var credInput = document.getElementById('academic_credentials');
		if (credInput) {
			credInput.addEventListener('change', function () {
				var file = credInput.files && credInput.files[0];
				if (!file) return;
				var maxBytes = 5 * 1024 * 1024;
				if (file.size > maxBytes) {
					alert('Academic credentials file must not exceed 5 MB.');
					credInput.value = '';
				}
			});
		}
	});
})();
