//This function is used when the value of a front end UI element is changed to sync all UI element values to the backend.
function BackEndUISync(mode,project_state)
{
    //console.log("BackEndUISync called with mode: " + mode + " and project_state: " + project_state);
    //console.log("Project state: " + project_state);
    if (mode == 0) //Normal mode
    {
        //Project state of 0 means the backend AQuA instance has not been initalized and thus there is nothing to sync
        //Project state of 1 means we are in new project mode. So we only sync certain fh values to backend
        if (project_state == 1)
        {
            UActionPayloadSC("BEUISync",["NPTC1FDI",10000000,document.getElementById("NewProjTabCh1FileDirInput").value, //{05}
            "NPTC2FDI",10000000,document.getElementById("NewProjTabCh2FileDirInput").value, //{08}
            "NPTDTDDx",10000000,document.getElementById("NewProjTabDataTypeDropDown").selectedIndex, //{11}
            "NPTTRIxx",10000000,document.getElementById("NewProjTabTempResInput").value, //{14}
            "NPTSRIxx",10000000,document.getElementById("NewProjTabSpatialResInputInput").value, //{17}
            "NPTEPIxx",10000000,document.getElementById("NewProjTabExcludePixelsInput").value]); //{20}
        }
        if (project_state == 2)
        {
            UActionPayloadSC("BEUISync",["NPTC1FDI",10000000,document.getElementById("NewProjTabCh1FileDirInput").value, //{05}
            "NPTC2FDI",10000000,document.getElementById("NewProjTabCh2FileDirInput").value, //{08}
            "NPTDTDDx",10000000,document.getElementById("NewProjTabDataTypeDropDown").selectedIndex, //{11}
            "NPTTRIxx",10000000,document.getElementById("NewProjTabTempResInput").value, //{14}
            "NPTSRIxx",10000000,document.getElementById("NewProjTabSpatialResInputInput").value, //{17}
            "NPTEPIxx",10000000,document.getElementById("NewProjTabExcludePixelsInput").value, //{20}
            "LTMinBSx",10000000,document.getElementById("LayersMinSlider").value, //{23}
            "LTMaxBSx",10000000,document.getElementById("LayersMaxSlider").value, //{26}
            "LTBriSLx",10000000,document.getElementById("LayersBrightnessSlider").value, //{29}
            "LTBriSRx",10000000,document.getElementById("LayersBrightness2Slider").value, //{32}
            "MTMovSLx",10000000,document.getElementById("MoviePanelFrameSlider").value, //{35}
            "DPTDTRGx",10000000,document.getElementById("DetectPipeTab1RegistrateDropdown").selectedIndex, //For backend reference {38}
            "DPTDTPBx",10000000,document.getElementById("DetectPipeTab1PhotobleachDropdown").selectedIndex, //{41}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab1MedianFilterRadius").value, //{44}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab1GaussianFilterRadius").value, //{47}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab2IntensityThresInput").value, //{50} Intensity threshold scaling factor [number]
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab2MinDurInput").value, //{53}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab2MinSizeInput").value, //{56}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab2MaxSizeInput").value, //{59}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab2CircularityThresholdInput").value, //{62}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab3EnableTempSegToggle").checked, //{65}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab3ZscoreSigSeedInput").value, //{68} Zscore of seed significance
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab3RiseTimeDiffThresInput").value, //{71} //Allowed maximum dissimilarity in merging
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab3RefineTempAdjacentPeaks").checked, //{74}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab3GrowActiveRegionPerPatterns").checked, //{77}
            "xxxxxxxx",10000000,DetectPipelineCurrentTab, //{80}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab4EnableSpatialSegToggle").checked, //{83}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab4SourceSizeRatio").value, //{86}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab4SourceDetectSensitivity").value, //{89}
            "xxxxxxxx",10000000,0, //{92} Deprecated
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab5DetectGlobalSignalToggle").checked, //{95}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab6IgnoreDelayTauToggle").checked, //{98}
            "xxxxxxxx",10000000,document.getElementById("ExportTabProjectToggle").checked, //{101}
            "xxxxxxxx",10000000,document.getElementById("ExportTabFeatureTableToggle").checked, //{104}
            "xxxxxxxx",10000000,document.getElementById("ExportTabMovieWithOverlayToggle").checked, //{107}
            "xxxxxxxx",10000000,document.getElementById("LayersColorBrightnessSlider").value, //{110}
            "xxxxxxxx",10000000,document.getElementById("dropDownFeatureOverlayType").selectedIndex, //{113}
            "xxxxxxxx",10000000,document.getElementById("dropDownFeatureOverlayFeature").selectedIndex, //For backend reference {116}
            "xxxxxxxx",10000000,document.getElementById("dropDownFeatureOverlayColor").selectedIndex, //For backend reference {119}
            "xxxxxxxx",10000000,viewportCurrentTab, //For backend reference {122},
            "xxxxxxxx",10000000,document.getElementById("movLTypeDropdown").selectedIndex, //{125}
            "xxxxxxxx",10000000,document.getElementById("movRTypeDropdown").selectedIndex, //{128}
            "xxxxxxxx",10000000,document.getElementById("leftViewPortChannel").selectedIndex, //For backend reference {131}
            "xxxxxxxx",10000000,document.getElementById("rightViewPortChannel").selectedIndex, //For backend reference {134}
            "xxxxxxxx",10000000,document.getElementById("Viewport1DSValue").value, //{137}
            "xxxxxxxx",10000000,document.getElementById("Viewport2LDSValue").value, //{140}
            "xxxxxxxx",10000000,document.getElementById("Viewport2RDSValue").value, //For backend reference {143}
            "xxxxxxxx",10000000,document.getElementById("CurvePlotDSValue").value, //For backend reference {146}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab4TemporalExtensionToggle").checked, //{149}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab3SeedSizeRelativeInput").value, //{152}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab6PropagationMetricRelativeToggle").checked, //{155}
            "xxxxxxxx",10000000,document.getElementById("DetectPipeTab6NetworkFeaturesToggle").checked //{158}
            ]);
            
        }
    }
    if (mode == 1) //Special mode for redircting sync to PrstSync. We keep format similar but route within BE to sync only preset
    {
        UActionPayloadSC("PrstSync",["NPTDTDDx",10000000,document.getElementById("NewProjTabDataTypeDropDown").selectedIndex]);
    }
}