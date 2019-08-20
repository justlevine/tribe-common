var tribe = tribe || {};
tribe.dialogs = tribe.dialogs || {};

	( function ( obj ) {
		'use strict';

		document.addEventListener(
			'DOMContentLoaded',
			function () {
				tribe.dialogs.forEach(function(dialog) {
					var dialog_obj = new window.A11yDialog({
						appendTarget: dialog.appendTarget,
						bodyLock: dialog.bodyLock,
						closeButtonAriaLabel: dialog.closeButtonAriaLabel,
						closeButtonClasses: dialog.closeButtonClasses,
						contentClasses: dialog.contentClasses,
						effect: dialog.effect,
						effectEasing: dialog.effectEasing,
						effectSpeed: dialog.effectSpeed,
						overlayClasses: dialog.overlayClasses,
						overlayClickCloses: dialog.overlayClickCloses,
						trigger: dialog.trigger,
						wrapperClasses: dialog.wrapperClasses
					});

					dialog_obj.on('show', function (dialogEl, event) {
						event.preventDefault();
						event.stopPropagation();
					});
				});
			}
		)
	})(tribe.dialog);
