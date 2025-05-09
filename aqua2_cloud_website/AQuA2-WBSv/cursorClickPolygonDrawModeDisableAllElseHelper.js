function cursorClickPolygonDrawModeDisableAllElseHelper()
{
    //Project Panel
    document.getElementById("NewProjTabCh1FileDirInput").disabled = true; //Disable - Dont let user manually edit this field
    document.getElementById("NewProjTabCh1FileDirRefer").disabled = true;
    document.getElementById("NewProjTabCh2FileDirInput").disabled = true; //Disable - Dont let user manually edit this field
    document.getElementById("NewProjTabCh2FileDirRefer").disabled = true;
    document.getElementById("LoadProjTabCh1FileDirInput").disabled = true; //Disable - Dont let user manually edit this field
    document.getElementById("LoadProjTabFileDirRefer").disabled = true; //Disable
    document.getElementById("LoadProjTabButtonCreate").disabled = true; //Disable
    
    document.getElementById("NewProjTabDataTypeDropDown").disabled = true; //Disable this until they've selected a file for CH1 at least
    document.getElementById("NewProjTabTempResInput").disabled = true; //Disable this until they've selected a file for CH1 at least
    document.getElementById("NewProjTabSpatialResInputInput").disabled = true; //Disable this until they've selected a file for CH1 at least
    document.getElementById("NewProjTabExcludePixelsInput").disabled = true; //Disable this until they've selected a file for CH1 at least
    document.getElementById("NewProjTabButtonCreate").disabled = true;
    document.getElementById("NewProjTabButtonLoadPreset").disabled = true;
    
    //DRL panel
    document.getElementById("DRLCellAdd").disabled = true; //Disable
    document.getElementById("DRLCellRemove").disabled = true; //Disable
    document.getElementById("DRLCellName").disabled = true; //Disable
    document.getElementById("DRLCellSave").disabled = true; //Disable
    document.getElementById("DRLCellLoad").disabled = true; //Disable						
    document.getElementById("DRLLdMarkAdd").disabled = true; //Disable
    document.getElementById("DRLLdMarkRemove").disabled = true; //Disable
    document.getElementById("DRLLdMarkName").disabled = true; //Disable
    document.getElementById("DRLLdMarkSave").disabled = true; //Disable
    document.getElementById("DRLLdMarkLoad").disabled = true; //Disable
    
    document.getElementById("UIR_DrawAnteriorButton").disabled = true; //Disable
    document.getElementById("UIR_updateFeaturesButton").disabled = true; //Disable
    document.getElementById("UIR_ExtractRIOButton").disabled = true; //Disable
    
    //Detect Panel
    DetectionTabSwitchingAllowed = false;
    document.getElementById("DetectPipeTab1RegistrateDropdown").disabled = true; //Disable
    document.getElementById("DetectPipeTab1PhotobleachDropdown").disabled = true; //Disable
    document.getElementById("DetectPipeTab1MedianFilterRadius").disabled = true; //Disable
    document.getElementById("DetectPipeTab1GaussianFilterRadius").disabled = true; //Disable
    document.getElementById("DetectPipeTab2IntensityThresInput").disabled = true; //Disable
    document.getElementById("DetectPipeTab2IntensityThresSlider").disabled = true; //Disable
    document.getElementById("DetectPipeTab2MinDurInput").disabled = true; //Disable
    document.getElementById("DetectPipeTab2MinSizeInput").disabled = true; //Disable
    document.getElementById("DetectPipeTab2MaxSizeInput").disabled = true; //Disable
    document.getElementById("DetectPipeTab2CircularityThresholdInput").disabled = true; //Disable
    //document.getElementById("DetectPipeTab2AllowedSameSignalDistance").disabled = true; //Disable
    //document.getElementById("DetectPipeTab2AdvancedFiltersToggle").disabled = true; //Disable
    document.getElementById("DetectPipeTab4TemporalExtensionToggle").disabled = true; //Disable
    document.getElementById("DetectPipeTab3EnableTempSegToggle").disabled = true; //Disable
    document.getElementById("DetectPipeTab3SeedSizeRelativeInput").disabled = true; //Disable
    document.getElementById("DetectPipeTab3ZscoreSigSeedInput").disabled = true; //Disable
    document.getElementById("DetectPipeTab3RiseTimeDiffThresInput").disabled = true; //Disable
    document.getElementById("DetectPipeTab3RefineTempAdjacentPeaks").disabled = true; //Disable
    document.getElementById("DetectPipeTab3GrowActiveRegionPerPatterns").disabled = true; //Disable
    document.getElementById("DetectPipeTab4EnableSpatialSegToggle").disabled = true; //Disable
    document.getElementById("DetectPipeTab4SourceSizeRatio").disabled = true; //Disable
    document.getElementById("DetectPipeTab4SourceDetectSensitivity").disabled = true; //Disable
    document.getElementById("DetectPipeTab5DetectGlobalSignalToggle").disabled = true; //Disable
    //document.getElementById("DetectPipeTab5GlobalSignalDuration").disabled = true; //Disable
    document.getElementById("DetectPipeTab6IgnoreDelayTauToggle").disabled = true; //Disable
    document.getElementById("DetectPipeTab6PropagationMetricRelativeToggle").disabled = true; //Disable
    document.getElementById("DetectPipeTab6NetworkFeaturesToggle").disabled = true; //Disable
    
    document.getElementById("DetectPipeBackButton").disabled = true; //Disable
    document.getElementById("DetectPipeRunButton").disabled = true; //Disable
    document.getElementById("DetectPipeNextButton").disabled = true; //Disable
    document.getElementById("DetectPipeSaveButton").disabled = true; //Disable
    document.getElementById("DetectPipeLoadButton").disabled = true; //Disable
    document.getElementById("DetectPipeRunAllStepsButton").disabled = true; //Disable
    
    
    //Layers Panel
    document.getElementById("LayersMinSlider").disabled = true; //Disable
    document.getElementById("LayersMaxSlider").disabled = true; //Disable
    document.getElementById("LayersBrightnessSlider").disabled = true; //Disable
    document.getElementById("LayersBrightness2Slider").disabled = true; //Disable
    document.getElementById("LayersColorBrightnessSlider").disabled = true; //Disable
    document.getElementById("dropDownFeatureOverlayType").disabled = true; //Disable
    document.getElementById("dropDownFeatureOverlayFeature").disabled = true; //Disable
    document.getElementById("dropDownFeatureOverlayColor").disabled = true; //Disable
    document.getElementById("UIR_updateOverlayButton").disabled = true; //Disable
    
    //Proof reading panel
    document.getElementById("UIR_ProofReadingViewFavButton").disabled = true; //Disable
    document.getElementById("UIR_ProofReadingDeleteRestoreButton").disabled = true; //Disable
    document.getElementById("UIR_ProofReadingAddAllFilteredButton").disabled = true; //Disable
    document.getElementById("UIR_ProofReadingFeaturesPlotButton").disabled = true; //Disable
    
    //Favourites panel
    
    //Export tab
    document.getElementById("ExportTabProjectToggle").disabled = true; //Disable
    document.getElementById("ExportTabFeatureTableToggle").disabled = true; //Disable
    document.getElementById("ExportTabMovieWithOverlayToggle").disabled = true; //Disable
    document.getElementById("ExportTabExportButton").disabled = true; //Disable
    
    //Movie panel
    ViewportTabSwitchingAllowed = false;
    document.getElementById("MoviePanelGaussFilterButton").disabled = true; //Disable
    document.getElementById("MoviePanelJumpToInput").disabled = true; //Disable
    document.getElementById("movLTypeDropdown").disabled = true; //Disable
    document.getElementById("movRTypeDropdown").disabled = true; //Disable
    document.getElementById("MoviePanelSingleFrameLeftButton").disabled = true; //Disable
    document.getElementById("MoviePanelSingleFrameRightButton").disabled = true; //Disable
    document.getElementById("MoviePanelFrameSlider").disabled = true; //Disable
    document.getElementById("MoviePanelnEvtInput").disabled = true; //Disable
    
    //Downsampling Control Bar
    document.getElementById("Viewport1DSValue").disabled = true; //Disable
    document.getElementById("Viewport2LDSValue").disabled = true; //Disable
    document.getElementById("Viewport2RDSValue").disabled = true; //Disable
    document.getElementById("CurvePlotDSValue").disabled = true; //Disable
    document.getElementById("DSQualityButtonLow").disabled = true; //Disable
    document.getElementById("DSQualityButtonMed").disabled = true; //Disable
    document.getElementById("DSQualityButtonFull").disabled = true; //Disable
}