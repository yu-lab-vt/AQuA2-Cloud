<!-- instance link information bar -->
<div class="table-responsive" id="instanceInfoTableTop">
	<table class="table table-bordered table-hover table-sm bg-grey" id="instanceInfoTable">
		<tfoot id="abc">
			<tr id="abb">
				<td class="gray" colspan="1" >
					<div id="instanceInfoText" class="badge badge-light">Awaiting instance linking...</a></div>
				</td>
			</tr>
		</tfoot>
	</table>
</div>

<!-- Viewports downsampling Fields -->
<div id="viewPortDSTableDiv">
	<table id="viewPortDSTable">
		<tr height="30px">
			<td class="gray" colspan="1">
				<p class="u-custom-font u-text u-text-body-color u-text-default u-text-17">Single Viewport Downsample Ratio: </p>
			</td>
			<td class="gray" colspan="1">
				<input type="text" id="Viewport1DSValue" style="height:20px; width:20px;font-size:10px;" title="Define the downsampling ratio for the viewport (single mode). A ratio of 0.75 means the image sent from server is 75% the size of the original, which allows for faster refresh and interface load times. Lowering this ratio is recommended if you have long load times or a slow connection. This is purely a visual and speed function, the imaging data is still processed at full resolution within the back-end.">
			</td>
			<td class="gray" colspan="1">
				<p class="u-custom-font u-text u-text-body-color u-text-default u-text-17">Dual Viewport Left Image Downsample Ratio: </p>
			</td>
			<td class="gray" colspan="1">
				<input type="text" id="Viewport2LDSValue" style="height:20px; width:20px;font-size:10px;" title="Define the downsampling ratio for the left viewport (dual mode). A ratio of 0.75 means the image sent from server is 75% the size of the original, which allows for faster refresh and interface load times. Lowering this ratio is recommended if you have long load times or a slow connection. This is purely a visual and speed function, the imaging data is still processed at full resolution within the back-end.">
			</td>
			<td class="gray" colspan="1">
				<p class="u-custom-font u-text u-text-body-color u-text-default u-text-17">Dual Viewport Right Image Downsample Ratio: </p>
			</td>
			<td class="gray" colspan="1">
				<input type="text" id="Viewport2RDSValue" style="height:20px; width:20px;font-size:10px;" title="Define the downsampling ratio for the right viewport (dual mode). A ratio of 0.75 means the image sent from server is 75% the size of the original, which allows for faster refresh and interface load times. Lowering this ratio is recommended if you have long load times or a slow connection. This is purely a visual and speed function, the imaging data is still processed at full resolution within the back-end.">
			</td>
			<td class="gray" colspan="1">
				<p class="u-custom-font u-text u-text-body-color u-text-default u-text-17">Curve Plot Downsample Ratio: </p>
			</td>
			<td class="gray" colspan="1">
				<input type="text" id="CurvePlotDSValue" style="height:20px; width:20px;font-size:10px;" title="Define the downsampling ratio for the curve viewport A ratio of 0.75 means the curve image sent from server is 75% the size of the original, which allows for faster refresh and interface load times. Lowering this ratio is recommended if you have long load times or a slow connection. This is purely a visual and speed function, the curve is still full size within the back-end.">
			</td>
			<td class="gray" colspan="1">
				<p class="u-custom-font u-text u-text-body-color u-text-default u-text-17">Quality Presets</p>
			</td>
			<td class="gray" colspan="1">
				<a id="DSQualityButtonLow" onclick="UIR_DSQualityButtonLow()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-22">50%</a>
			</td>
			<td class="gray" colspan="1">
				<a id="DSQualityButtonMed" onclick="UIR_DSQualityButtonMed()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-22">75%</a>
			</td>
			<td class="gray" colspan="1">
				<a id="DSQualityButtonFull" onclick="UIR_DSQualityButtonFull()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-22">Full</a>
			</td>
		</tr>
	</table>
</div>