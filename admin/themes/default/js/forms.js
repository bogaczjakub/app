jQuery(document).ready(function() {
	$("form#search-form .search-selector-list li a").on(
		"click",
		pickSearchCategory
	);

	function pickSearchCategory(event) {
		event.preventDefault();
		const searchCategory = $(this).attr("href");
		const hiddenSelector = $("form#search-form").find(".search-selector-input");
		const searchButton = $("form#search-form ").find(".search-selector-text");
		searchButton.text(searchCategory);
		hiddenSelector.val(searchCategory);
	}

	$("form[method=get]").submit(function(event) {
		const formMethod = $(this).attr("method");
		const formAction = $(this).attr("action");
		if (formMethod.toLocaleLowerCase() == "get" && formAction.length > 0) {
			const formSerialize = $(this).serialize();
			const location = formAction + "&" + formSerialize;
			window.location.href = location;
		}
		return false;
	});

	$(".admin-form .btn-switch button").on("click", switchButtonAction);

	function switchButtonAction(event) {
		const parent = $(this).parent(".btn-switch");
		if (parent.hasClass("switch-off")) {
			$(this)
				.siblings(".btn-switch-input")
				.val(1);
			parent.removeClass("switch-off").addClass("switch-on");
		} else if (parent.hasClass("switch-on")) {
			$(this)
				.siblings(".btn-switch-input")
				.val(0);
			parent.removeClass("switch-on").addClass("switch-off");
		}
	}

	$(".admin-form table tbody tr").on("click", tableRowClick);

	function tableRowClick(event) {
		const $this = $(event.currentTarget);
		const checkbox = $this.find('input[type="checkbox"]');
		if (checkbox) {
			$(checkbox).click();
			if ($(checkbox).is(":checked")) {
				$this.addClass("selected-row");
			} else {
				$this.removeClass("selected-row");
			}
		}
	}

	$(".admin-form table thead tr th .sort").on("click", sortChange);

	function sortChange(event) {
		$this = $(this);
		if ($this.hasClass("asc")) {
			$this.removeClass("asc").addClass("desc");
		} else if ($this.hasClass("desc")) {
			$this.removeClass("desc").addClass("asc ");
		} else {
			$this.addClass("asc");
		}
	}

	$(".admin-form .change-password-button").on("click", toggleChangePassword);

	function toggleChangePassword(event) {
		const $this = event.currentTarget;
		const box = $($this).siblings(".change-password-container");
		const hiddenInput = $($this).siblings(".change-password");
		if (box.is(":hidden")) {
			box.show();
		} else {
			box.hide();
			hiddenInput.val(0);
		}
	}

	$(".admin-form .change-password-container .input-group-addon").on(
		"click",
		showPassword
	);

	function showPassword(event) {
		const $this = $(event.currentTarget);
		const passwordInput = $this.siblings("input");
		if (passwordInput[0].getAttribute("type") == "password") {
			passwordInput[0].setAttribute("type", "text");
		} else {
			passwordInput[0].setAttribute("type", "password");
		}
	}
});
