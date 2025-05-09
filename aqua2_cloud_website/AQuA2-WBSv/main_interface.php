<link rel="stylesheet" href="nicepage.css" media="screen">
<script class="u-script" type="text/javascript" src="jquery.js"></script>
<script class="u-script" type="text/javascript" src="nicepage.js"></script>
<link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">
<link id="u-page-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata:200,300,400,500,600,700,800,900">
<link rel="stylesheet" href="AQuA_WBSv.css" media="screen">
<body class="u-body u-xl-mode" data-lang="en">
    <section class="u-clearfix u-hidden-lg u-hidden-md u-hidden-sm u-hidden-xs u-section-1" id="sec-2caf">
      
      <!-- Formatting/Behaviour/Style Patching. This patches finalize the functional positioning, behaviour, and appearance of some UI elements -->
	  <link rel="stylesheet" type="text/css" href="paddingFix.css">

      <div class="u-container-style u-expanded-width u-group u-shape-rectangle u-group-1">
        <div class="u-container-layout u-container-layout-1">
          <div class="u-border-1 u-border-grey-50 u-container-style u-custom-color-2 u-group u-group-2">
            <div id="projectTabsContainer" class="u-container-layout u-container-layout-2">
              <div class="u-container-style u-custom-color-1 u-expanded-width u-group u-group-3">
                <div class="u-container-layout u-container-layout-3">
                  <p id="InstancePanelTitle" class="u-custom-font u-text u-text-default u-text-1">Project Management</p>
                </div>
              </div>
              <div class="u-tab-links-align-justify u-tabs u-tabs-1">
                <ul class="u-tab-list u-unstyled" role="tablist">
                  <li class="u-tab-item" role="presentation">
                    <a class="active u-active-custom-color-1 u-border-1 u-border-grey-50 u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-20 u-tab-link u-text-active-white u-text-hover-white u-tab-link-1" id="link-tab-3f86" href="#tab-3f86" role="tab" aria-controls="tab-3f86" aria-selected="true">New Project</a>
                  </li>
                  <li class="u-tab-item" role="presentation">
                    <a class="u-active-custom-color-1 u-border-1 u-border-grey-50 u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-20 u-tab-link u-text-active-white u-text-hover-white u-tab-link-2" id="link-tab-3b33" href="#tab-3b33" role="tab" aria-controls="tab-3b33" aria-selected="false">Load Project</a>
                  </li>
                </ul>
                <div class="u-tab-content">
                  <div class="u-container-style u-tab-active u-tab-pane" id="tab-3f86" role="tabpanel" aria-labelledby="link-tab-3f86">
                    <div class="u-container-layout u-container-layout-4">
                      <p class="u-custom-font u-text u-text-body-color u-text-2">Movie (TIFF Stack) CH1 (Required) </p>
                      <div class="u-clearfix u-custom-html u-expanded-width u-custom-html-1">
                        <input type="text" id="NewProjTabCh1FileDirInput" style="height:20px; width:283px;font-size:10px;" disabled="">
                      </div>
                      <a id="NewProjTabCh1FileDirRefer" onclick="UIR_NewProjTabCh1FileDirRefer()" title="Set the currently selected file as the imaging movie file that will be loaded into a new project as channel 1. Clicking this button with no file selected will clear the channel 1 file field." class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-1">Link Referenced File/Directory</a>
                      <p class="u-custom-font u-text u-text-body-color u-text-3">Movie (TIFF Stack) CH2 (Optional) </p>
                      <div class="u-clearfix u-custom-html u-expanded-width u-custom-html-2">
                        <input type="text" id="NewProjTabCh2FileDirInput" style="height:20px; width:283px;font-size:10px;" disabled="">
                      </div>
                      <a id="NewProjTabCh2FileDirRefer" onclick="UIR_NewProjTabCh2FileDirRefer()" title="Set the currently selected file as the imaging movie file that will be loaded into a new project as channel 2. Clicking this button with no file selected will clear the channel 2 file field." class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-2">Link Referenced File/Directory</a>
                      <div class="u-clearfix u-custom-html u-custom-html-3">
                        <select id="NewProjTabDataTypeDropDown" style="height:20px; width:120px;font-size:10px;background-color: #FFFFFF;color:black;" title="Selected preset of settings for the project. This allows one to configure settings presets with respect to common data types one might use. 'Load Preset' can be used to load a .csv file containing presets custom defined by the user.">
                          <option value="default" selected="None">Default</option>
                        </select>
                      </div>
                      <div class="u-clearfix u-custom-html u-custom-html-4">
                        <input type="text" id="NewProjTabTempResInput" style="height:20px; width:80px;font-size:10px;" title="The temporal resolution of the imaging data in seconds per frame.">
                      </div>
                      <div class="u-clearfix u-custom-html u-custom-html-5">
                        <input type="text" id="NewProjTabSpatialResInputInput" style="height:20px; width:80px;font-size:10px;" title="The spatial resolution of the imaging data in microns per pixel.">
                      </div>
                      <p id="textSecondsPerFrame" class="u-custom-font u-text u-text-body-color u-text-default u-text-4">Seconds per frame</p>
                      <p id="textMicronsPerPixel" class="u-custom-font u-text u-text-body-color u-text-default u-text-5">Microns per pixel</p>
                      <div class="u-clearfix u-custom-html u-custom-html-6">
                        <input type="text" id="NewProjTabExcludePixelsInput" style="height:20px; width:80px;font-size:10px;" title="The number of pixels to exclude from the edges of the imaging movie. This is useful for excluding pixels that are not in focus or are otherwise unusable.">
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-6">Pixel Border Exclusion</p>
                      <div class="u-container-style u-custom-color-2 u-expanded-width u-group u-group-4">
                        <div id="projectTabsButtonContainer" class="u-container-layout u-container-layout-5">
                          <a id="NewProjTabButtonCreate" onclick="NewProjTabButtonCreate()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-3" title="Create a new project using the selected imaging movie, settings preset, and the above specified parameters for temporal/spatial resolution and pixel border exclusion.">Create New Project</a>
                          <a id="NewProjTabButtonLoadPreset" onclick="UIR_NewProjTabButtonLoadPreset()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-4" title="Load a .csv file containing AQuA project presets. The currently referenced file (whatever file is selected in the file browser above) will be the file used. Note that due to technical limitations, only 3 presets from a given .csv can be loaded.">Load Preset</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="u-container-style u-tab-pane" id="tab-3b33" role="tabpanel" aria-labelledby="link-tab-3b33">
                    <div class="u-container-layout u-container-layout-6">
                      <p class="u-custom-font u-text u-text-body-color u-text-7">AQuA2 Project (.mat)(Required)</p>
                      <div class="u-clearfix u-custom-html u-custom-html-7">
                        <input type="text" id="LoadProjTabCh1FileDirInput" style="height:20px; width:283px;font-size:10px;">
                      </div>
                      <a id="LoadProjTabFileDirRefer" onclick="UIR_LoadProjTabFileDirRefer()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-5">Link Referenced File/Directory</a>
                      <a id="LoadProjTabButtonCreate" onclick="UIR_LoadProjTabButtonCreate()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-6" title="Load a .mat file which was exported previously by an AQuA2-Cloud instance or via AQuA2 in MATLAB.">Load Project</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="u-border-1 u-border-grey-50 u-shape u-shape-svg u-text-custom-color-2 u-shape-1">
            <svg class="u-svg-link" preserveAspectRatio="none" viewBox="0 0 160 160" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-72ef"></use></svg>
            <svg class="u-svg-content" viewBox="-0.5 -0.5 161 161" x="0px" y="0px" id="svg-72ef"><path d="M160,160H0V0L160,160z"></path></svg>
          </div>
          <div class="u-border-1 u-border-grey-50 u-container-style u-custom-color-2 u-group u-group-5">
            <div class="u-container-layout u-container-layout-7" id="DRLUIContainer">
              <div class="u-container-style u-custom-color-1 u-expanded-width u-group u-group-6">
                <div class="u-container-layout u-container-layout-8">
                  <p class="u-custom-font u-text u-text-default u-text-8">Directions, Regions, &amp; Landmarks</p>
                </div>
              </div>
              <div class="u-container-style u-custom-color-2 u-expanded-width u-group u-group-7" id="DRLUIContainer02">
                <div class="u-container-layout u-container-layout-9">
                  <p class="u-text u-text-default u-text-9">Cell boundary</p>
                  <a id="DRLCellAdd" onclick="UIR_DRLCellAdd()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-7" title="Activate mode to draw and define a cell region by placing points, then closing the shape. This will put you into polygon draw mode.">+</a>
                  <a id="DRLCellRemove" onclick="UIR_DRLCellRemove()"  class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-8" title="Activate mode to delete defined cell regions by clicking in the center of them. This will put you into cursor click mode.">-</a>
                  <a id="DRLCellName" onclick="UIR_DRLCellName()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-10" title="Define a cell region's name. A text entry dialog box will appear, then you will be put into cursor click mode.">Name</a>
                  <a id="DRLCellSave" onclick="UIR_DRLCellSave()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-11" title="Save all defined cell regions to a .mat file that AQuA2 can use. A text entry dialog box will appear.">Save</a>
                  <a id="DRLCellLoad"  onclick="UIR_DRLCellLoad()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-12" title="Load a .mat file, previously exported by AQuA via the Directions, Regions, and Landmarks DRL/LDMK save feature, to use a predefined set of regions. A text entry dialog box will appear.">Load</a>
                </div>
              </div>
              <div class="u-container-style u-custom-color-2 u-expanded-width u-group u-group-8" id="DRLUIContainer03">
                <div class="u-container-layout u-container-layout-10">
                  <p class="u-text u-text-default u-text-10">Landmark</p>
                  <a id="DRLLdMarkAdd" onclick="UIR_DRLLdMarkAdd()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-13" title="Activate mode to draw and define landmark regions by placing points, then closing the shape. This will put you into polygon draw mode.">+</a>
                  <a id="DRLLdMarkRemove" onclick="UIR_DRLLdMarkRemove()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-14" title="Activate mode to delete defined landmark regions by clicking in the center of them. This will put you into cursor click mode.">-</a>
                  <a id="DRLLdMarkName" onclick="UIR_DRLLdMarkName()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-16" title="Define a landmark region's name. A text entry dialog box will appear, then you will be put into cursor click mode.">Name</a>
                  <a id="DRLLdMarkSave" onclick="UIR_DRLLdMarkSave()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-17" title="Save all defined landmark regions to a .mat file that AQuA can use. A text entry dialog box will appear.">Save</a>
                  <a id="DRLLdMarkLoad" onclick="UIR_DRLLdMarkLoad()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-18" title="Load a .mat file, previously exported by AQuA via the Directions, Regions, and Landmarks DRL/LDMK save feature, to use a predefined set of regions. A text entry dialog box will appear.">Load</a>
                </div>
              </div>
              <div class="u-container-style u-custom-color-2 u-expanded-width u-group u-group-9" id="DRLUIContainer04">
                <div class="u-container-layout u-container-layout-11">
                  <a id="UIR_DrawAnteriorButton" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-19">-------------</a>
                  <a id="UIR_updateFeaturesButton" onclick="UIR_updateFeaturesButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-20" title="Recalculate the features of detected events.">Update features</a>
                  <a id="UIR_ExtractRIOButton" onclick="UIR_ExtractRIOButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-21" title="Enter polygon drawing mode to draw a polygon within the optical field for which you'd like to observe the curve of mean intensity.">ROI Curve</a>
                </div>
              </div>
            </div>
          </div>
          <div class="u-border-1 u-border-grey-50 u-container-style u-custom-color-2 u-group u-group-10" id="DetectionContainer01">
            <div class="u-container-layout u-container-layout-12">
              <div class="u-container-style u-custom-color-1 u-group u-group-11">
                <div class="u-container-layout u-container-layout-13">
                  <p class="u-custom-font u-text u-text-default u-text-11">Detection</p>
                </div>
              </div>
              <div class="u-tab-links-align-justify u-tabs u-tabs-2">
                <ul class="u-tab-list u-unstyled" role="tablist">
                  <li onclick="UIR_DetectPipelineTabClick(1)" class="u-tab-item" role="presentation">
                    <a class="active u-active-custom-color-1 u-border-1 u-border-grey-50 u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-20 u-tab-link u-text-active-white u-text-hover-white u-tab-link-3" id="DetectPipePreProcessTab" href="#tab-be41" role="tab" aria-controls="tab-be41" aria-selected="true">[1] Pre<br>process
                    </a>
                  </li>
                  <li onclick="UIR_DetectPipelineTabClick(2)" class="u-tab-item" role="presentation">
                    <a class="u-active-custom-color-1 u-border-1 u-border-grey-50 u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-20 u-tab-link u-text-active-white u-text-hover-white u-tab-link-4" id="DetectPipeActiveTab" href="#tab-821b" role="tab" aria-controls="tab-821b" aria-selected="false">[2] Active</a>
                  </li>
                  <li onclick="UIR_DetectPipelineTabClick(3)" class="u-tab-item" role="presentation">
                    <a class="u-active-custom-color-1 u-border-1 u-border-grey-50 u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-20 u-tab-link u-text-active-white u-text-hover-white u-tab-link-5" id="DetectPipeTemporalTab" href="#tab-d59d" role="tab" aria-controls="tab-d59d" aria-selected="false">[3] Temporal</a>
                  </li>
                  <li onclick="UIR_DetectPipelineTabClick(4)" class="u-tab-item u-tab-item-6" role="presentation">
                    <a class="u-active-custom-color-1 u-border-1 u-border-grey-50 u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-20 u-tab-link u-text-active-white u-text-hover-white u-tab-link-6" id="DetectPipeSpatialTab" href="#tab-a49b" role="tab" aria-controls="tab-a49b" aria-selected="false">[4] Spatial</a>
                  </li>
                  <li onclick="UIR_DetectPipelineTabClick(5)" class="u-tab-item u-tab-item-7" role="presentation">
                    <a class="u-active-custom-color-1 u-border-1 u-border-grey-50 u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-20 u-tab-link u-text-active-white u-text-hover-white u-tab-link-7" id="DetectPipeGlobalTab" href="#tab-7b6c" role="tab" aria-controls="tab-7b6c" aria-selected="false">[5] Global</a>
                  </li>
                  <li onclick="UIR_DetectPipelineTabClick(6)" class="u-tab-item u-tab-item-8" role="presentation">
                    <a class="u-active-custom-color-1 u-border-1 u-border-grey-50 u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-20 u-tab-link u-text-active-white u-text-hover-white u-tab-link-8" id="DetectPipeFeatureTab" href="#tab-669a" role="tab" aria-controls="tab-669a" aria-selected="false">[6] Feature</a>
                  </li>
                </ul>
                <div class="u-tab-content">
                  <div class="u-container-style u-custom-color-3 u-tab-active u-tab-pane u-tab-pane-3" id="tab-be41" role="tabpanel" aria-labelledby="link-tab-be41">
                    <div class="u-container-layout u-container-layout-14">
                      <p class="u-custom-font u-text u-text-body-color u-text-12">Registration</p>
                      <div class="u-clearfix u-custom-html u-expanded-width u-custom-html-8">
                        <select name="dropdown" id="DetectPipeTab1RegistrateDropdown" style="height:20px; width:284px;font-size:10px;background-color: #FFFFFF;color:black;">
                          <option value="No registration" selected="None">No registration</option>
                          <option value="Rigid registration by cross correlation based on channel 1">Rigid registration by cross correlation based on channel 1</option>
                          <option value="Rigid registration by cross correlation based on channel 2">Rigid registration by cross correlation based on channel 2</option>
                        </select>
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-13">Photobleach Correction</p>
                      <div class="u-clearfix u-custom-html u-expanded-width u-custom-html-9">
                        <select name="dropdown" id="DetectPipeTab1PhotobleachDropdown" style="height:20px; width:284px;font-size:10px;background-color: #FFFFFF;color:black;">
                          <option value="No registration" selected="None">No bleach correction</option>
                          <option value="Remove bleach globally">Remove bleach globally</option>
                          <option value="Remove bleach by intensity">Remove bleach by intensity</option>
                        </select>
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-14">Salt and Pepper Noise Removal (0 = None)</p>
                      <div class="u-clearfix u-custom-html u-custom-html-10">
                        <input type="text" id="DetectPipeTab1MedianFilterRadius" style="height:20px; width:80px;font-size:10px;" title="The radius of median filter applied to the data (please only use it when there is salt and pepper noise). For instance, 0 means no filter, 1 means apply a 3X3 median filter.">
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-15">Median filter radius</p>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-16">Baseline modeling and noise modeling</p>
                      <div class="u-clearfix u-custom-html u-custom-html-11">
                        <input type="text" id="DetectPipeTab1GaussianFilterRadius" style="height:20px; width:80px;font-size:10px;" title="The radius/standard deviation of 2D Gaussian filter applied in spatial dimension. It is used to improve SNR.">
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-17">Gaussian filter radius</p>
                    </div>
                  </div>
                  <div class="u-container-style u-custom-color-3 u-tab-pane u-tab-pane-4" id="tab-821b" role="tabpanel" aria-labelledby="link-tab-821b">
                    <div class="u-container-layout u-container-layout-15">
                      <p class="u-custom-font u-text u-text-body-color u-text-18">Thresholding/Filtering</p>
                      <div class="u-clearfix u-custom-html u-custom-html-12">
                        <input type="text" id="DetectPipeTab2IntensityThresInput" style="height:20px; width:80px;font-size:10px;" title="We use threshold * noise standard deviation to select active regions. Larger threshold will result in a stricter detection.">
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-19">Intensity threshold&nbsp;scaling</p>
                      <div class="u-clearfix u-custom-html u-hover-feature u-custom-html-13">
                        <div class="slidecontainer">
                          <input type="range" min="1" max="100" step="0.1" value="50" class="slider" id="DetectPipeTab2IntensityThresSlider" style="height:20px;width:283px;" title="Variably adjust the intensity threshold scaling.">
                        </div>
                      </div>
                      <div class="u-clearfix u-custom-html u-custom-html-14">
                        <input type="text" id="DetectPipeTab2MinDurInput" style="height:20px; width:80px;font-size:10px;" title="Activity events with duration less than this minimum duration will be filtered out.">
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-20">Minimum duration (frames)</p>
                      <div class="u-clearfix u-custom-html u-custom-html-15">
                        <input type="text" id="DetectPipeTab2MinSizeInput" style="height:20px; width:80px;font-size:10px;" title="Activity events with spatial territory smaller than this minimum size in total pixels will be filtered out.">
                      </div>
                      <div class="u-clearfix u-custom-html u-custom-html-16">
       
                        <br>
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-21">Minimum size (pixels)</p>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-22">Advanced filters</p>
                      <div class="u-clearfix u-custom-html u-custom-html-17">
                        <input type="text" id="DetectPipeTab2MaxSizeInput" style="height:20px; width:80px;font-size:10px;" title="Activity events with spatial territory larger than this maximum size in total pixels will be filtered out.">
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-23">Maximum size (pixels)</p>
                      <div class="u-clearfix u-custom-html u-custom-html-18">
                        <input type="text" id="DetectPipeTab2CircularityThresholdInput" style="height:20px; width:80px;font-size:10px;" title="Activity events with weird shapes will be filtered out. 0 means no limitation. 1 means the regions' shapes should be perfect circle.">
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-24">Active region circularity</p>
                      <div class="u-clearfix u-custom-html u-custom-html-19">
                      </div>
                    </div>
                  </div>
                  <div class="u-container-style u-custom-color-3 u-tab-pane u-tab-pane-5" id="tab-d59d" role="tabpanel" aria-labelledby="link-tab-d59d">
                    <div class="u-container-layout u-container-layout-16">
                      <div class="u-clearfix u-custom-html u-custom-html-20">
                        <input type="checkbox" id="DetectPipeTab3EnableTempSegToggle" name="vehicle1" value="Bike" title="One region may have multiple temporal peaks. If enabled: system will do temporal segmentation. If disabled: system will not run this step.">
                        <br>
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-26">Temporal segmentation required</p>
                      <div class="u-clearfix u-custom-html u-custom-html-21">
                        <input type="text" id="DetectPipeTab3SeedSizeRelativeInput" style="height:20px; width:80px;font-size:10px;" title="AQuA2 will detect seed regions that have significant peak pattern, then do segmentation based on these seeds. Here it set a minimum size of spatial territory for seed region to avoid artifacts. The relative ratio should be from 0 to 1.">
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-27">Seed size relative to region</p>
                      <div class="u-clearfix u-custom-html u-custom-html-22">
                        <input type="text" id="DetectPipeTab3ZscoreSigSeedInput" style="height:20px; width:80px;font-size:10px;" title="The significance threshold for judging whether one region is seed or not. Larger threshold will detect fewer seeds.">
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-28">Zscore of seed significance</p>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-29">Region merging</p>
                      <div class="u-clearfix u-custom-html u-custom-html-23">
                        <input type="text" id="DetectPipeTab3RiseTimeDiffThresInput" style="height:20px; width:80px;font-size:10px;" title="One metric of dissimilarity between two temporal patterns, considering both delay and duration. The merging step is to merge the seed regions with similar temporal patterns.">
                      </div>
                      <p class="u-align-left u-custom-font u-text u-text-body-color u-text-default u-text-30">Max dissimilarity</p>
                      <div class="u-clearfix u-custom-html u-custom-html-24">
                        <input type="checkbox" id="DetectPipeTab3RefineTempAdjacentPeaks" name="vehicle1" value="Bike" title="Above operations may be not strong enough for temporal segmentation. Check this will do a further segmentation.">
                        <br>
                      </div>
                      <p class="u-align-left u-custom-font u-text u-text-body-color u-text-default u-text-31">Refine temporally adjacent peaks</p>
                      <div class="u-clearfix u-custom-html u-custom-html-25">
                        <input type="checkbox" id="DetectPipeTab3GrowActiveRegionPerPatterns" name="vehicle1" value="Bike" title="Check this will grow the regions spatially with similar temporal patterns.">
                        <br>
                      </div>
                      <p class="u-align-left u-custom-font u-text u-text-body-color u-text-default u-text-32">Grow active regions per signal patterns</p>
                    </div>
                  </div>
                  <div class="u-container-style u-custom-color-3 u-tab-pane u-tab-pane-6" id="tab-a49b" role="tabpanel" aria-labelledby="link-tab-a49b">
                    <div class="u-container-layout u-container-layout-17">
                      <div class="u-clearfix u-custom-html u-custom-html-26">
                        <input type="checkbox" id="DetectPipeTab4EnableSpatialSegToggle" name="vehicle1" value="Bike" title="One super event may comes from multiple spatial sources. If enabled: system will do spatial segmentation. If disabled: system will not segment spatially.">
                        <br>
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-33">Spatial segmentation required</p>
                      <div class="u-clearfix u-custom-html u-custom-html-27">
                        <input type="text" id="DetectPipeTab4SourceSizeRatio" style="height:20px; width:80px;font-size:10px;" title="AQuA2 will detect source regions that arise signal earlier, then do segmentation based on these sources. Here it set a minimum size of spatial territory for source region to avoid artifacts. The relative ratio should be from 0 to 1.">
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-34">Source size</p>
                      <div class="u-clearfix u-custom-html u-custom-html-28">
                        <input type="text" id="DetectPipeTab4SourceDetectSensitivity" style="height:20px; width:80px;font-size:10px;" title="The sensitivity to for juding one local part as source. Larger sensitivity will make it easier to be segmented.">
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-default u-text-35">Source detection sensitivity<br>[0-10]
                      <div class="u-clearfix u-custom-html u-custom-html-26">
						<input type="checkbox" id="DetectPipeTab4TemporalExtensionToggle" name="vehicle1" value="Bike" title="The voxels of detected events are all above the threshold you set before, but it may not occupy the whole duratoin of signal. Check it will extend the detected results temporally">
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-33">Temporal Extension</p>
                      </p>
                    </div>
                  </div>
                  <div class="u-container-style u-custom-color-3 u-tab-pane u-tab-pane-7" id="tab-7b6c" role="tabpanel" aria-labelledby="link-tab-7b6c">
                    <div class="u-container-layout u-container-layout-18">
                      <div class="u-clearfix u-custom-html u-custom-html-29">
                        <input type="checkbox" id="DetectPipeTab5DetectGlobalSignalToggle" name="vehicle1" value="Bike" title="If checked system, will detect global signals that are hidden below the events we detected before.">
                        <br>
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-36">Detection of global signal needed?</p>
                    </div>
                  </div>
                  <div class="u-container-style u-custom-color-3 u-tab-pane u-tab-pane-8" id="tab-669a" role="tabpanel" aria-labelledby="link-tab-669a">
                    <div class="u-container-layout u-container-layout-19">
                      <div class="u-clearfix u-custom-html u-custom-html-30">
                        <input type="checkbox" id="DetectPipeTab6IgnoreDelayTauToggle" name="vehicle1" value="Bike" title="If enabled, system it will not calculate the expotential decay feature for each event. Such feature is usually designed for neuronal signals.">
                        <br>
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-37">Ignore delay tau</p>
                      <div class="u-clearfix u-custom-html u-custom-html-31">
                        <input type="checkbox" id="DetectPipeTab6PropagationMetricRelativeToggle" name="vehicle1" value="Bike" title="If enabled, system will calculate propagation features, but this may take some time to do so.">
                        <br>
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-38">Propagation metric relative to starting point in different directions&nbsp; (Propagation map is already calculated)<br>
                      </p>
                      <div class="u-clearfix u-custom-html u-custom-html-32">
                        <input type="checkbox" id="DetectPipeTab6NetworkFeaturesToggle" name="vehicle1" value="Bike" title="If enabled, system will calculate network features (the relation with cell boundaries/landmarks you draw), but may take some time to do so.">
                        <br>
                      </div>
                      <p class="u-custom-font u-text u-text-body-color u-text-39">Network features</p>
                    </div>
                  </div>
                </div>
              </div>
              <a id="DetectPipeBackButton" onclick="UIR_DetectPipeBackButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-22" title="Return to the previous detection pipeline step.">Back</a>
              <a id="DetectPipeRunButton" onclick="UIR_DetectPipeRunButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-23" title="Run the current detection pipeline step.">Run</a>
              <a id="DetectPipeNextButton" onclick="UIR_DetectPipeNextButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-24" title="Proceed to the next detection pipeline step.">Next</a>
              <a id="DetectPipeSaveButton" onclick="UIR_DetectPipeSaveButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-20 u-custom-font u-hover-custom-color-18 u-btn-25" title="Save settings used within this configured detection pipeline to a .csv file.">Save</a>
              <a id="DetectPipeLoadButton" onclick="UIR_DetectPipeLoadButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-20 u-custom-font u-hover-custom-color-18 u-btn-26" title="Load previously saved pipeline settings from a .csv file.">Load</a>
              <a id="DetectPipeRunAllStepsButton" onclick="UIR_DetectPipeRunAllStepsButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-20 u-custom-font u-hover-custom-color-18 u-btn-27" title="Immediately run all of the steps of the detection pipeline.">Run all steps</a>
            </div>
          </div>
          <div class="u-border-1 u-border-grey-50 u-container-style u-custom-color-2 u-group u-group-12" id="LayeringContainer01">
            <div class="u-container-layout u-container-layout-20">
              <div class="u-container-style u-custom-color-1 u-expanded-width u-group u-group-13">
                <div class="u-container-layout u-container-layout-21">
                  <p class="u-custom-font u-text u-text-default u-text-40">Layering</p>
                </div>
              </div>
              <p class="u-align-center u-custom-font u-text u-text-body-color u-text-41">Movie brightness &amp; c<span style="text-decoration: underline !important;"></span>ontrast
              </p>
              <p class="u-align-left u-custom-font u-text u-text-body-color u-text-42">Min</p>
              <div class="u-clearfix u-custom-html u-hover-feature u-custom-html-33">
                <div class="slidecontainer">
                  <input type="range" min="1" max="1" value="0.5" step="0.01" class="slider" id="LayersMinSlider" style="height:20px;width:284px;" <="" div="">
                </div>
              </div>
              <p class="u-align-left u-custom-font u-text u-text-body-color u-text-43">Max</p>
              <div class="u-clearfix u-custom-html u-hover-feature u-custom-html-34">
                <div class="slidecontainer">
                  <input type="range" min="1" max="1" value="0.5" step="0.01" class="slider" id="LayersMaxSlider" style="height:20px;width:284px;">
                </div>
              </div>
              <p id="LayersBrightnessSliderTitle" class="u-align-left u-custom-font u-text u-text-body-color u-text-44">Main/Left Brightness</p>
              <p id="LayersBrightness2SliderTitle" class="u-align-left u-custom-font u-text u-text-body-color u-text-45">Right Brightness</p>
              <div class="u-clearfix u-custom-html u-hover-feature u-custom-html-35">
                <div class="slidecontainer">
                  <input type="range" min="1" max="10" value="5" step="0.1" class="slider" id="LayersBrightnessSlider" style="height:20px;width:130px;">
                </div>
              </div>
              <div class="u-clearfix u-custom-html u-hover-feature u-custom-html-36">
                <div class="slidecontainer">
                  <input type="range" min="1" max="10" value="5" step="0.1" class="slider" id="LayersBrightness2Slider" style="height:20px;width:130px;">
                </div>
              </div>
              <p class="u-align-left u-custom-font u-text u-text-body-color u-text-46">Colorful Overlay Brightness</p>
              <div class="u-clearfix u-custom-html u-hover-feature u-custom-html-37">
                <div class="slidecontainer">
                  <input type="range" min="1" max="1" value="0.5" step="0.01" class="slider" id="LayersColorBrightnessSlider" style="height:20px;width:284px;">
                </div>
              </div>
              <p class="u-align-center u-custom-font u-text u-text-body-color u-text-49">Feature Overlay</p>
              <p class="u-align-left u-custom-font u-text u-text-body-color u-text-51">Channel in Left Viewport (2CH Mode Only)</p>
              <div class="u-clearfix u-custom-html u-custom-html-40">
                <select name="dropdown" id="leftViewPortChannel" style="height:20px; width:284px;font-size:10px;background-color: #FFFFFF;color:black;">
					<option value="CH1" selected="CH1">Channel 1</option>
					<option value="CH2">Channel 2</option>
                </select>
              </div>
              <p class="u-align-left u-custom-font u-text u-text-body-color u-text-51">Channel in Right Viewport (2CH Mode Only)</p>
              <div class="u-clearfix u-custom-html u-custom-html-40">
                <select name="dropdown" id="rightViewPortChannel" style="height:20px; width:284px;font-size:10px;background-color: #FFFFFF;color:black;">
					<option value="CH1" selected="CH1">Channel 1</option>
					<option value="CH2">Channel 2</option>
                </select>
              </div>
              <p class="u-align-left u-custom-font u-text u-text-body-color u-text-51">Type</p>
              <div class="u-clearfix u-custom-html u-custom-html-40">
                <select name="dropdown" id="dropDownFeatureOverlayType" style="height:20px; width:284px;font-size:10px;background-color: #FFFFFF;color:black;">
                  <option value="None" selected="None">None</option>
                </select>
              </div>
              <p class="u-align-left u-custom-font u-text u-text-body-color u-text-51">Feature</p>
              <div class="u-clearfix u-custom-html u-custom-html-41">
                <select name="dropdown" id="dropDownFeatureOverlayFeature" style="height:20px; width:284px;font-size:10px;background-color: #FFFFFF;color:black;">
					<option value="Index" selected="Index">Index</option>
					<option value="Starting Frame">Starting Frame</option>
					<option value="Basic - Area">Basic - Area</option>
					<option value="Basic - Perimeter (Only for 2D video)">Basic - Perimeter (Only for 2D video)</option>
					<option value="Basic - Surface Size (Only for 3D video)">Basic - Surface Size (Only for 3D video)</option>
					<option value="Basic - Circularity">Basic - Circularity</option>
					<option value="Curve - P Value on max Dff (-log10)">Basic - P Value on max Dff (-log10)</option>
					<option value="Curve - Max Dff">Basic - Max Dff</option>
					<option value="Curve - Duration 50% to 50%">Curve - Duration 50% to 50%</option>
					<option value="Curve - Duration 10% to 10%">Curve - Duration 10% to 10%</option>
					<option value="Curve - Rising duration 10% to 90%">Curve - Rising duration 10% to 90%</option>
					<option value="Curve - Decaying duration 90% to 10%">Curve - Decaying duration 90% to 10%</option>
					<option value="Curve - dat AUC">Curve - dat AUC</option>
					<option value="Curve - dff AUC">Curve - dff AUC</option>
					<option value="Propagation - onset - overall">Propagation - onset - overall</option>
					<option value="Propagation - onset - one direction">Propagation - onset - one direction</option>
					<option value="Propagation - onset - one direction - ratio">Propagation - oneset - one direction - ratio</option>
					<option value="Propagation - offset - overall">Propagation - offset - overall</option>
					<option value="Propagation - offset - one direction">Propagation - offset - one direction</option>
					<option value="Propagation - offset - one direction - ratio">Propagation - offset - one direction - ratio</option>
                </select>
              </div>
              <p class="u-align-left u-custom-font u-text u-text-body-color u-text-52">Color</p>
              <div class="u-clearfix u-custom-html u-custom-html-42">
                <select name="dropdown" id="dropDownFeatureOverlayColor" style="height:20px; width:284px;font-size:10px;background-color: #FFFFFF;color:black;">
                  <option value="Random" selected="Random">Random</option>
                  <option value="GreenRed">GreenRed</option>
                  <option value="RdBu">RdBu</option>
                  <option value="RdYlBu">RdYlBu</option>
                  <option value="YlGnBu">YlGnBu</option>
                </select>
              </div>
              <a id="UIR_updateOverlayButton" onclick="UIR_LayersUpdateOverlayButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-28" title="Update the overlay according to the options you selected above.">Update overlay</a>
            </div>
          </div>
          <div id="movieBoxSFPatch01" class="u-container-style u-group u-palette-2-light-2 u-group-14">
            <div class="u-container-layout u-container-layout-22">
              <div class="u-border-1 u-border-grey-50 u-container-style u-grey-10 u-group u-group-15">
                <div class="u-container-layout u-container-layout-23">
                  <div class="u-clearfix u-custom-html u-custom-html-48">
                    <label style="color: black;font-size: 12px;font-family: 'Inconsolata';" for="jumpto">Jump to:</label>
                    <input type="text" id="MoviePanelJumpToInput" style="height:20px; width:27px;font-size:10px;" title="Enter a frame number for which to set the current displayed frame to.">
                  </div>
                  <a id="MoviePanelGaussFilterButton"  onclick="UIR_MoviePanelGaussFilterButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-29" title="Enabling this will show a smoother image in raw data.">Gauss Filter<br>
                  </a>
                </div>
              </div>
              <div class="u-border-1 u-border-grey-50 u-container-style u-custom-color-2 u-group u-group-16">
                <div class="u-container-layout u-container-layout-24">
                  <p id="MoviePanelCurrentFrameIndicator" class="u-custom-font u-text u-text-body-color u-text-default u-text-58">Frame xxxxx/xxxxx</p>
                  <div class="u-clearfix u-custom-html u-custom-html-49">
                    <select name="dropdown" id="movLTypeDropdown" style="height:20px; width:225px;font-size:10px;background-color: #FFFFFF;color:black;">
                      <option value="Raw" selected="Raw">Raw</option>
                      <option value="Raw+overlay">Raw + overlay</option>
                      <option value="Maximum projection">Maximum projection</option>
                      <option value="Average projection">Average projection</option>
                      <option value="dF/sigma">dF/sigma</option>
					  <option value="Threshold preview">Threshold preview</option>
                      <option value="Rising preview (20%)">Rising preview (20%)</option>
					  <option value="Rising preview (50%)">Rising preview (50%)</option>
					  <option value="Rising preview (80%)">Rising preview (80%)</option>
                    </select>
                  </div>
                  <div id="rightViewportModeSelect" class="u-clearfix u-custom-html u-custom-html-50">
                    <select name="dropdown" id="movRTypeDropdown" style="height:20px; width:225px;font-size:10px;background-color: #FFFFFF;color:black;">
                      <option value="Raw" selected="Raw">Raw</option>
                      <option value="Raw+overlay">Raw + overlay</option>
                      <option value="Maximum projection">Maximum projection</option>
                      <option value="Average projection">Average projection</option>
                      <option value="dF/sigma">dF/sigma</option>
					  <option value="Threshold preview">Threshold preview</option>
                      <option value="Rising preview (20%)">Rising preview (20%)</option>
					  <option value="Rising preview (50%)">Rising preview (50%)</option>
					  <option value="Rising preview (80%)">Rising preview (80%)</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="u-expanded-width u-tabs u-tabs-3">
                <ul class="u-tab-list u-unstyled" role="tablist">
                  <li onclick="UIR_ViewportModeTabClick(1)" class="u-tab-item" role="presentation">
                    <a class="active u-active-custom-color-1 u-border-1 u-border-grey-50 u-button-style u-custom-color-3 u-hover-custom-color-20 u-tab-link u-text-active-white u-text-hover-white u-tab-link-9" id="SingleViewPortTab" href="#tab-b1b5" role="tab" aria-controls="tab-b1b5" aria-selected="true">Single Viewport</a>
                  </li>
                  <li onclick="UIR_ViewportModeTabClick(2)" class="u-tab-item" role="presentation">
                    <a class="u-active-custom-color-1 u-border-1 u-border-grey-50 u-button-style u-custom-color-3 u-hover-custom-color-20 u-tab-link u-text-active-white u-text-hover-white u-tab-link-10" id="DualViewPortTab" href="#tab-b1b5" role="tab" aria-controls="tab-b1b5" aria-selected="false">Dual Viewport</a>
                  </li>
                </ul>
                <div class="u-tab-content" id="movieBoxViewportTabContainer">
                  <div class="u-container-style u-custom-color-2 u-tab-active u-tab-pane u-tab-pane-9" id="tab-b1b5" role="tabpanel" aria-labelledby="link-tab-b1b5">
						<canvas id="MoviePanelImagePrimary" onmousedown="canvasClick(event)">
                  </div>
                </div>
              </div>
              <div class="u-border-1 u-border-grey-50 u-container-style u-custom-color-2 u-expanded-width u-group u-group-18">
                <div class="u-container-layout u-container-layout-30">
                  <div class="u-container-style u-custom-color-2 u-group u-group-19">
                    <div class="u-container-layout u-container-layout-31">
                    </div>
                  </div>
                  <div class="u-container-style u-group u-shape-rectangle u-group-20">
                    <div class="u-container-layout u-container-layout-32">
                      <div class="u-clearfix u-custom-color-2 u-custom-html u-custom-html-51">
                        <label style="color: black;font-size: 12px;font-family: 'Inconsolata';" for="noofevents">No. of events</label>
                        <input type="text" id="MoviePanelnEvtInput" style="height:20px; width:80px;font-size:10px;" title="Total number of events detected within imaging data by AQuA">
                      </div>
                    </div>
                  </div>
                  <div id="movieBoxSliderDivPatch" class="u-container-style u-group u-palette-1-light-2 u-group-21">
                    <div class="u-container-layout u-container-layout-33">
                      <a id="MoviePanelSingleFrameRightButton" onclick="UIR_MoviePanelSingleFrameRightButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-34">&gt;</a>
                      <a id="MoviePanelSingleFrameLeftButton"  onclick="UIR_MoviePanelSingleFrameLeftButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-35">&lt;</a>
                      <div id="displaySliderDiv" class="u-clearfix u-custom-html u-hover-feature u-custom-html-52">
                        <div class="slidecontainer">
                          <input type="range" min="1" max="100" value="50" class="slider" id="MoviePanelFrameSlider">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </section>
    <section class="u-clearfix u-custom-color-15 u-hidden-lg u-hidden-md u-hidden-sm u-hidden-xs u-valign-middle u-section-2" id="sec-9f5f">
      <div class="u-container-style u-expanded-width u-group u-white u-group-1">
        <div class="u-container-layout u-container-layout-1">
          <div class="u-border-1 u-border-grey-50 u-container-style u-custom-color-2 u-group u-group-2" id="ProofReadingContainer">
            <div class="u-container-layout u-container-layout-2">
              <div class="u-container-style u-custom-color-1 u-expanded-width u-group u-group-3">
                <div class="u-container-layout u-container-layout-3">
                  <p class="u-custom-font u-text u-text-default u-text-1">Proof Reading</p>
                </div>
              </div>
              <a id="UIR_ProofReadingViewFavButton" onclick="UIR_ProofReadingViewFavButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-1" title="Enter cursor click mode to select events within the viewport. Selecting an event within the viewport will cause the event to appear in the favourites table and its curve shown in the curve plot.">View/Favourite</a>
              <a id="UIR_ProofReadingDeleteRestoreButton" onclick="UIR_ProofReadingDeleteRestoreButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-2" title="Enter cursor click mode to select an event for which you'd like to hide its corresponding color pixels within the optical field. This is useful if you have multiple overlapping events and would like to hide some events to reveal others. Note that this does not delete an event entirely. Use the checkboxes and delete button within the favourites panel to delete events.">Delete/Restore</a>
              <div class="u-border-1 u-border-grey-75 u-clearfix u-custom-html u-hover-feature u-custom-html-1">
                <div id="proofReadingTable-wrapper">
                  <div id="proofReadingTable-scroll">
                    <Table id="proofReadingTable-table" border="1">
                      <thead>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <a id="UIR_ProofReadingAddAllFilteredButton" onclick="UIR_ProofReadingAddAllFilteredButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-3" title="Add all detected events that meet the filtering criteria defined above to the favourites table.">Add all filtered</a>
              <a id="UIR_ProofReadingFeaturesPlotButton" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-4">-------------</a>
            </div>
          </div>
          <div class="u-border-1 u-border-grey-50 u-container-style u-custom-color-2 u-group u-group-4" id="FavouriteContainer">
            <div class="u-container-layout u-container-layout-4">
              <div class="u-container-style u-custom-color-1 u-expanded-width u-group u-group-5">
                <div class="u-container-layout u-container-layout-5">
                  <p class="u-custom-font u-text u-text-default u-text-2">Favourite</p>
                </div>
              </div>
              <a id="UIR_FavouritesTabSelectAllButton" onclick="UIR_FavouritesTabSelectAllButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-5"title="Select all events shown in this favourites table.">Select all</a>
              <a id="UIR_FavouritesTabDeleteButton" onclick="UIR_FavouritesTabDeleteButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-7" title="Delete all events that have their checkboxes toggled within this favourites table. Note that you can still re-add events by using the view/favourites button within the proof reading panel or via the 'Add all filtered' button.">Delete</a>
              <a id="UIR_FavouritesTabShowCurvesButton" onclick="UIR_FavouritesTabShowCurvesButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-8"title="When you have one or more events toggled within the table below, this button will display the intensity curve(s) for the single or all events selected within the curve plot.">Show curves</a>
              <a id="UIR_FavouritesTabSaveCurvesButton" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-9">-----------</a>
              <a id="UIR_FavouritesTabSaveWavesButton" onclick="UIR_FavouritesTabSaveWavesButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-10"title="Save the intensity curves for all selected events to .csv. Note that multiple files and folders are created depending on the number of selected events.">Save waves</a>
              <div class="u-border-1 u-border-grey-75 u-clearfix u-custom-html u-hover-feature u-custom-html-2">
                <div id="favouritetable-wrapper">
                  <div id="favouritetable-scroll">
                    <table id="favouriteTable-table" border="1">
                      <thead>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="u-clearfix u-custom-html u-custom-html-3">
                <label style="color: black;font-size: 12px;font-family: 'Inconsolata';" for="jumpto">Ch1 ID</label>
                <input type="text" id="UIR_toolsAddEvt1" style="height:20px; width:40px;font-size:10px;" title="Add an event detected within CH1 to the favourites table by event ID number.">
              </div>
              <a id="UIR_toolsAddEvt1Button" onclick="UIR_toolsAddEvt1Button()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-11">Add</a>
              <div class="u-clearfix u-custom-html u-custom-html-4">
                <label style="color: black;font-size: 12px;font-family: 'Inconsolata';" for="jumpto">Ch2 ID</label>
                <input type="text" id="UIR_toolsAddEvt2" style="height:20px; width:40px;font-size:10px;" title="Add an event detected within CH2 to the favourites table by event ID number.">
              </div>
              <a id="UIR_toolsAddEvt2Button" onclick="UIR_toolsAddEvt2Button()"class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-12">Add</a>
            </div>
          </div>
          <div class="u-border-1 u-border-grey-50 u-container-style u-custom-color-2 u-group u-group-6" id="ExportContainer">
            <div class="u-container-layout u-container-layout-6">
              <div class="u-container-style u-custom-color-1 u-expanded-width u-group u-group-7">
                <div class="u-container-layout u-container-layout-7">
                  <p class="u-custom-font u-text u-text-default u-text-3">Export</p>
                </div>
              </div>
              <div class="u-clearfix u-custom-html u-custom-html-5">
                <input type="checkbox" id="ExportTabProjectToggle" name="vehicle1" value="Bike" title="Select whether to save the project files (which contains all results) in your target folder. It can be loaded later in both AQuA2-Cloud and MATLAB version of AQuA2.">
                <br>
              </div>
              <p class="u-custom-font u-text u-text-body-color u-text-default u-text-4">AQuA2-Cloud/AQuA2 MATLAB Project</p>
              <div class="u-clearfix u-custom-html u-custom-html-6">
                <input type="checkbox" id="ExportTabFeatureTableToggle" name="vehicle1" value="Bike" title="Select whether to export the features of detected events in your target folder.">
                <br>
              </div>
              <p class="u-custom-font u-text u-text-body-color u-text-default u-text-5">Feature Table</p>
              <div class="u-clearfix u-custom-html u-custom-html-7">
                <input type="checkbox" id="ExportTabMovieWithOverlayToggle" name="vehicle1" value="Bike" title="Select whether to export the movie with detected events overlaying on it in your target folder. May take some time.">
                <br>
              </div>
              <p class="u-custom-font u-text u-text-body-color u-text-default u-text-6">Movie with overlay</p>
              <a id="ExportTabExportButton" onclick="UIR_ExportTabExportButton()" class="u-border-1 u-border-custom-color-19 u-border-hover-black u-btn u-button-style u-custom-color-3 u-custom-font u-hover-custom-color-18 u-btn-13" title="Export all the items selected above to the target folder.">Export</a>
            </div>
          </div>
		  
		  <div id="graphDisplayFrame" class="u-container-align-center u-container-style u-group u-shape-rectangle">
			<canvas id="graphDisplay">
		  </div>
        </div>
      </div>	  
	  
	  <div id="detailstable-box" class="u-border-1 u-border-grey-75 u-clearfix u-custom-html u-hover-feature u-custom-html-2">
		<div id="detailstable-wrapper">
		  <div id="detailstable-scroll">
		  </div>
		</div>
	  </div>
    </section>
    <section class="u-clearfix u-hidden-xl u-section-3" id="sec-bd85">
      <div class="u-clearfix u-sheet u-sheet-1">
        <p class="u-align-center-lg u-align-center-md u-align-center-sm u-align-center-xs u-custom-font u-text u-text-default-lg u-text-default-md u-text-default-sm u-text-default-xs u-text-1"></p>
      </div>
    </section>
	<div class="overlay">
	</div>

</body></html>