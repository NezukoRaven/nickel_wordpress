<?php wp_nonce_field( 'save_settings', 'form_settings_nonce' ); ?>
<div class="w-100">
		<div class="elex-raq-toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999">
		<div id="elex-raq-updated-sucess-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">

			<div class="toast-body text-center d-flex justify-content-between align-items-center">
				<h6 class="my-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="22.415" height="22.026" viewBox="0 0 22.415 22.026">
						<g id="Icon_feather-check-circle" data-name="Icon feather-check-circle" transform="translate(-1.998 -1.979)">
							<path id="Path_646" data-name="Path 646" d="M23,12.076V13a10,10,0,1,1-5.93-9.139" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
							<path id="Path_647" data-name="Path 647" d="M26.5,6l-10,10.009-3-3" transform="translate(-3.5 -1.003)" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
						</g>
					</svg>
					<span class="ms-2">
					<?php esc_html_e('Updated Successfully.', 'elex-request-a-quote'); ?>

					</span>
				</h6>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>
	</div>
</div>

<div class="w-100">
		<div class="elex-raq-toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999">
		<div id="elex-raq-deleted" class="toast" role="alert" aria-live="assertive" aria-atomic="true">

			<div class="toast-body text-center d-flex justify-content-between align-items-center">
				<h6 class="my-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="22.415" height="22.026" viewBox="0 0 22.415 22.026">
						<g id="Icon_feather-check-circle" data-name="Icon feather-check-circle" transform="translate(-1.998 -1.979)">
							<path id="Path_646" data-name="Path 646" d="M23,12.076V13a10,10,0,1,1-5.93-9.139" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
							<path id="Path_647" data-name="Path 647" d="M26.5,6l-10,10.009-3-3" transform="translate(-3.5 -1.003)" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
						</g>
					</svg>
					<span class="ms-2">
					<?php esc_html_e('Deleted Successfully.', 'elex-request-a-quote'); ?>
					</span>
				</h6>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>
	</div>
</div>
<div class="w-100">
		<div class="elex-raq-toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999">
		<div id="elex-raq-saved-sucess-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">

			<div class="toast-body text-center d-flex justify-content-between align-items-center">
				<h6 class="my-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="22.415" height="22.026" viewBox="0 0 22.415 22.026">
						<g id="Icon_feather-check-circle" data-name="Icon feather-check-circle" transform="translate(-1.998 -1.979)">
							<path id="Path_646" data-name="Path 646" d="M23,12.076V13a10,10,0,1,1-5.93-9.139" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
							<path id="Path_647" data-name="Path 647" d="M26.5,6l-10,10.009-3-3" transform="translate(-3.5 -1.003)" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
						</g>
					</svg>
					<span class="ms-2">
					<?php esc_html_e('Saved Successfully', 'elex-request-a-quote'); ?>
					</span>
				</h6>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>
	</div>
</div>

<div class="w-100">
		<div class="elex-raq-toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999">
		<div id="elex-raq-duplicate-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">

			<div class="toast-body text-center d-flex justify-content-between align-items-center">
				<h6 class="my-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="22.415" height="22.026" viewBox="0 0 22.415 22.026">
						<g id="Icon_feather-check-circle" data-name="Icon feather-check-circle" transform="translate(-1.998 -1.979)">
							<path id="Path_646" data-name="Path 646" d="M23,12.076V13a10,10,0,1,1-5.93-9.139" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
							<path id="Path_647" data-name="Path 647" d="M26.5,6l-10,10.009-3-3" transform="translate(-3.5 -1.003)" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
						</g>
					</svg>
					<span class="ms-2"><?php esc_html_e('Field with same name Already Exist', 'elex-request-a-quote'); ?></span>
				</h6>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>
	</div>
</div>

<div class="w-100">
		<div class="elex-raq-toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999">
		<div id="elex-raq-minimum-options-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">

			<div class="toast-body text-center d-flex justify-content-between align-items-center">
				<h6 class="my-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="22.415" height="22.026" viewBox="0 0 22.415 22.026">
						<g id="Icon_feather-check-circle" data-name="Icon feather-check-circle" transform="translate(-1.998 -1.979)">
							<path id="Path_646" data-name="Path 646" d="M23,12.076V13a10,10,0,1,1-5.93-9.139" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
							<path id="Path_647" data-name="Path 647" d="M26.5,6l-10,10.009-3-3" transform="translate(-3.5 -1.003)" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
						</g>
					</svg>
					<span class="ms-2"><?php esc_html_e('Enter 2 or more values for the selected input type.', 'elex-request-a-quote'); ?></span>
				</h6>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>
	</div>
</div>
<?php
echo '<div id="form_settings"></div>';
