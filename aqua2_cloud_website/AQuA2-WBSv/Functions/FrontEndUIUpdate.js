function FrontEndUIUpdate(project_state, data)
{
    //The recieved data array is size 3*n with n being the number of arguments passed back. The array is always min size of 1 due to the project state being passed
    //back as the first argument in this array.
    project_state = Number(data[2]); //Javascript is index 0
    
    //Set/Reset from cursor click/draw mode
    cursorCurrentMode = "";
    document.getElementById("MoviePanelImagePrimary").style.cursor = "default";
    canvasDrawMode = false;
    cd_complete = false;
    switch(project_state)
    {
        //Project state of 1 means we are in new project mode. So we only update the relevant section of the UI while leaving the rest disabled
        case 1:
            /*
                5 ["xxxxfIn1"] [10000000] [fh.fIn1.Value]...
                8 ["xxxxfIn2"] [10000000] [fh.fIn2.Value]...
                11 ["xxpreset"] [10000000] [fh.preset.Value]...
                14 ["xxtmpRes"] [10000000] [fh.tmpRes.Value]...
                17 ["xxspaRes"] [10000000] [fh.spaRes.Value]...
                20 ["xxxbdSpa"] [10000000] [fh.bdSpa.Value]...
                23 ["imagePri"] [10000000] [encodedImageSrc]... %This is primary encoded image in specific format compatible with WBS FE AQuA UI Display
                26 ["regiCrct"] [10000000] [fh.registrateCorrect.Value]...
                29 ["bleaCrct"] [10000000] [fh.bleachCorrect.Value]...
                32 ["xsldminv"] [10000000] [fh.sldMin.Value]...
                35 ["xsldmaxv"] [10000000] [fh.sldMax.Value]...
                38 ["sldBri1v"] [10000000] [fh.sldBri1.Value]...
                41 ["sldBri2v"] [10000000] ["0"]... %Was fh.sldBri2.Value
                44 ["xsldBriL"] [10000000] [fh.sldBriL.Value]...
                47 ["xsldBriR"] [10000000] [fh.sldBriR.Value]...
                50 ["sldLimOv"] [10000000] ["0"]... %Was fh.sldMinOv.Value
                53 ["sldMaxOv"] [10000000] ["0"]... %Was fh.sldMaxOv.Value]
                56 ["sldBriOv"] [10000000] [fh.sldBriOv.Value]...
                59 ["sldMovMx"] [10000000] [fh.sldMov.Limits(2)]... %Was fh.sldMov.Max
                62 ["sldMovVa"] [10000000] [fh.sldMov.Value]...
                65 ["tabsVStt"] [10000000] [opts.enableTab]...
                68 ["tabsCATx"] [10000000] [find(fh.deOutTab.SelectedTab==fh.deOutTab.Children)]... %Was fh.deOutTab.Selection
                71 ["tabsNxBE"] [10000000] [fh.deOutNext.Enable]...
                74 ["tabsNxBV"] [10000000] [fh.deOutNext.Visible]...
                77 ["tabsBkBV"] [10000000] [fh.deOutBack.Visible]...
                80 ["tabsRnBS"] [10000000] [fh.deOutRun.Text]... %Was fh.deOutRun.String
                83 ["tabsNxBS"] [10000000] [fh.deOutNext.Text]... %Was fh.deOutNext.String
                86 ["tabsT1St"] [10000000] [tbtmp01]... %Was fh.deOutTab.TabEnables{1}
                89 ["tabsT2St"] [10000000] [tbtmp02]... %Was fh.deOutTab.TabEnables{2}
                92 ["tabsT3St"] [10000000] [tbtmp03]... %Was fh.deOutTab.TabEnables{3}
                95 ["tabsT4St"] [10000000] [tbtmp04]... %Was fh.deOutTab.TabEnables{4}
                98 ["tabsT5St"] [10000000] [tbtmp05]... %Was fh.deOutTab.TabEnables{5}
                101 ["tabsT6St"] [10000000] [tbtmp06]... %Was fh.deOutTab.TabEnables{6}
                104 ["smoXYxxx"] [10000000] [fh.smoXY.Value]...
                107 ["noiseEst"] [10000000] ["0"]... %was fh.noiseEstimation.Value
                110 ["thrArScl"] [10000000] [fh.thrArScl.Value]...
                113 ["MinDurxx"] [10000000] [fh.minDur.Value]...
                116 ["minSizex"] [10000000] [fh.minSize.Value]...
                119 ["maxSizex"] [10000000] [fh.maxSize.Value]...
                121 ["ciculThr"] [10000000] [fh.circularityThr.Value]...
                124 ["needTemp"] [10000000] [fh.needTemp.Value]...
                127 ["sigThrxx"] [10000000] [fh.sigThr.Value]...
                130 ["maxDelay"] [10000000] [fh.maxDelay.Value]...
                133 ["neeRefin"] [10000000] [fh.needRefine.Value]...
                136 ["neeGrowx"] [10000000] [fh.needGrow.Value]...
                139 ["neeSpaxx"] [10000000] [fh.needSpa.Value]...
                142 ["cRisexxx"] [10000000] ["0"]... %Was fh.cRise.Value
                145 ["cDelayxx"] [10000000] ["0"]... %Was.fh.cDelay.Value
                148 ["gtwSmoxx"] [10000000] ["0"]... %Was fh.gtwSmo.Text
                151 ["detctGlo"] [10000000] [fh.detectGlo.Value]...
                154 ["ignorTau"] [10000000] [fh.ignoreTau.Value]...
                157 ["regiCrtE"] [10000000] [convertCharsToStrings(fh.registrateCorrect.Enable)]...
                160 ["bleaCrtE"] [10000000] [convertCharsToStrings(fh.bleachCorrect.Enable)]...
                163 ["xxexpEvt"] [10000000] [fh.expEvt.Value]...
                166 ["xxxexpFt"] [10000000] [fh.expFt.Value]...
                167 ["xxexpMov"] [10000000] [fh.expMov.Value]...
                170 ["xexpEvt2"] [10000000] ["0"]... %Was fh.expEvt2.Value
                173 ["exportVi"] [10000000] [fh.pExport.Visible]...
                176 ["preset01"] [10000000] [PreprocesspresetString01]...
                179 ["preset02"] [10000000] [PreprocesspresetString02]...
                182 ["preset03"] [10000000] [PreprocesspresetString03]
            */
            
            //******************************************************************************
            //Handle colors
            //#ffc7c7   -- Disabled red
            //#f0f0f0   -- Normal grey
            //#e1e1e1 - Panel grey
            //#d8d8d8 - Darker grey
            //#000000 - Black
            //#ff0000 - Red
            //#fffe00 - Yellow
            //#ffe891 - Caution/Pale Yellow
            document.getElementById("projectTabsContainer").style.backgroundColor = "#f0f0f0";
            document.getElementById("projectTabsButtonContainer").style.backgroundColor = "#f0f0f0";
            document.getElementById("DRLUIContainer").style.backgroundColor = "#ffc7c7";
            document.getElementById("DRLUIContainer02").style.backgroundColor = "#ffc7c7";
            document.getElementById("DRLUIContainer03").style.backgroundColor = "#ffc7c7";
            document.getElementById("DRLUIContainer04").style.backgroundColor = "#ffc7c7";
            document.getElementById("DetectionContainer01").style.backgroundColor = "#ffc7c7";
            document.getElementById("tab-be41").style.backgroundColor = "#ffc7c7";
            document.getElementById("tab-821b").style.backgroundColor = "#ffc7c7";
            document.getElementById("tab-d59d").style.backgroundColor = "#ffc7c7";
            document.getElementById("tab-a49b").style.backgroundColor = "#ffc7c7";
            document.getElementById("tab-7b6c").style.backgroundColor = "#ffc7c7";
            document.getElementById("tab-669a").style.backgroundColor = "#ffc7c7";
            document.getElementById("LayeringContainer01").style.backgroundColor = "#ffc7c7";
            
            document.getElementById("movieBoxSFPatch01").style.backgroundColor = "#ffc7c7";
            document.getElementById("movieBoxSFPatch01").style.borderColor = "#ff0000";
            document.getElementById("movieBoxSFPatch01").style.borderWidth = "1px";
            
            document.getElementById("ProofReadingContainer").style.backgroundColor = "#ffc7c7";
            document.getElementById("FavouriteContainer").style.backgroundColor = "#ffc7c7";
            document.getElementById("ExportContainer").style.backgroundColor = "#ffc7c7";
        
            //******************************************************************************
            
            //******************************************************************************
            //Handle element enabling/disabling
            
            //Indicate locking of new project panel
            document.getElementById("InstancePanelTitle").innerHTML = "Instance [Changes locked out]";
        
            //Project Panel
            document.getElementById("NewProjTabCh1FileDirInput").disabled = true; //Disable - Dont let user manually edit this field
            document.getElementById("NewProjTabCh1FileDirRefer").disabled = false;
            document.getElementById("NewProjTabCh2FileDirInput").disabled = true; //Disable - Dont let user manually edit this field
            document.getElementById("NewProjTabCh2FileDirRefer").disabled = false;
            document.getElementById("LoadProjTabCh1FileDirInput").disabled = true; //Disable - Dont let user manually edit this field
            document.getElementById("LoadProjTabFileDirRefer").disabled = false; //Disable
            document.getElementById("LoadProjTabButtonCreate").disabled = false; //Disable
            
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
            document.getElementById("DetectPipeTab3EnableTempSegToggle").disabled = true; //Disable
            document.getElementById("DetectPipeTab3SeedSizeRelativeInput").disabled = true; //Disable
            document.getElementById("DetectPipeTab3ZscoreSigSeedInput").disabled = true; //Disable
            document.getElementById("DetectPipeTab3RiseTimeDiffThresInput").disabled = true; //Disable
            document.getElementById("DetectPipeTab3RefineTempAdjacentPeaks").disabled = true; //Disable
            document.getElementById("DetectPipeTab3GrowActiveRegionPerPatterns").disabled = true; //Disable
            document.getElementById("DetectPipeTab4EnableSpatialSegToggle").disabled = true; //Disable
            document.getElementById("DetectPipeTab4TemporalExtensionToggle").disabled = true; //Disable
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
            document.getElementById("leftViewPortChannel").disabled = true; //Disable
            document.getElementById("rightViewPortChannel").disabled = true; //Disable
            
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
            
            //Clear the proofReading table and favourites Table
            document.getElementById("proofReadingTable-table").innerHTML = "";
            document.getElementById("favouriteTable-table").innerHTML = "";

            //Favourites panel
            document.getElementById("UIR_FavouritesTabSelectAllButton").disabled = true; //Disable
            document.getElementById("UIR_FavouritesTabDeleteButton").disabled = true; //Disable
            document.getElementById("UIR_FavouritesTabShowCurvesButton").disabled = true; //Disable
            document.getElementById("UIR_FavouritesTabSaveCurvesButton").disabled = true; //Disable
            document.getElementById("UIR_FavouritesTabSaveWavesButton").disabled = true; //Disable
            document.getElementById("UIR_toolsAddEvt1").disabled = true; //Disable
            document.getElementById("UIR_toolsAddEvt2").disabled = true; //Disable
            document.getElementById("UIR_toolsAddEvt1Button").disabled = true; //Disable
            document.getElementById("UIR_toolsAddEvt2Button").disabled = true; //Disable
            
            //******************************************************************************
            
            document.getElementById("NewProjTabCh1FileDirInput").value = data[5];
            document.getElementById("NewProjTabCh2FileDirInput").value = data[8];
            document.getElementById("NewProjTabTempResInput").value = data[14];
            document.getElementById("NewProjTabSpatialResInputInput").value = data[17];
            document.getElementById("NewProjTabExcludePixelsInput").value = data[20];
            
            if (document.getElementById("NewProjTabCh1FileDirInput").value == "")
            {
                document.getElementById("NewProjTabDataTypeDropDown").disabled = true; //Disable this until they've selected a file for CH1 at least
                document.getElementById("NewProjTabTempResInput").disabled = true; //Disable this until they've selected a file for CH1 at least
                document.getElementById("NewProjTabSpatialResInputInput").disabled = true; //Disable this until they've selected a file for CH1 at least
                document.getElementById("NewProjTabExcludePixelsInput").disabled = true; //Disable this until they've selected a file for CH1 at least
                document.getElementById("NewProjTabButtonCreate").disabled = true;
                document.getElementById("NewProjTabButtonLoadPreset").disabled = true;
            }
            else
            {
                document.getElementById("NewProjTabDataTypeDropDown").disabled = false; //Disable this until they've selected a file for CH1 at least
                document.getElementById("NewProjTabTempResInput").disabled = false; //Disable this until they've selected a file for CH1 at least
                document.getElementById("NewProjTabSpatialResInputInput").disabled = false; //Disable this until they've selected a file for CH1 at least
                document.getElementById("NewProjTabExcludePixelsInput").disabled = false; //Disable this until they've selected a file for CH1 at least
                document.getElementById("NewProjTabButtonCreate").disabled = false;
                document.getElementById("NewProjTabButtonLoadPreset").disabled = false;
            }
            
            //Process presets dropdown
            removeOptions(document.getElementById("NewProjTabDataTypeDropDown"));
            if (data[23])
            {
                document.getElementById("NewProjTabDataTypeDropDown").add(new Option(data[23], 1)); 
            }
            if (data[26])
            {
                document.getElementById("NewProjTabDataTypeDropDown").add(new Option(data[26], 1)); 
            }
            if (data[29])
            {
                document.getElementById("NewProjTabDataTypeDropDown").add(new Option(data[29], 1)); 
            }
            
            document.getElementById("NewProjTabDataTypeDropDown").selectedIndex = Number(data[11]) - 1;

            break;
        case 2:	
            //******************************************************************************
            //Handle colors
            //#ffc7c7   -- Disabled red
            //#f0f0f0   -- Normal grey
            //#e1e1e1 - Panel grey
            //#d8d8d8 - Darker grey
            //#000000 - Black
            //#ff0000 - Red
            //#fffe00 - Yellow
            document.getElementById("projectTabsContainer").style.backgroundColor = "#ffc7c7";
            document.getElementById("projectTabsButtonContainer").style.backgroundColor = "#ffc7c7";
            document.getElementById("DRLUIContainer").style.backgroundColor = "#f0f0f0";
            document.getElementById("DRLUIContainer02").style.backgroundColor = "#f0f0f0";
            document.getElementById("DRLUIContainer03").style.backgroundColor = "#f0f0f0";
            document.getElementById("DRLUIContainer04").style.backgroundColor = "#f0f0f0";
            document.getElementById("DetectionContainer01").style.backgroundColor = "#f0f0f0";
            document.getElementById("tab-be41").style.backgroundColor = "#f0f0f0";
            document.getElementById("tab-821b").style.backgroundColor = "#f0f0f0";
            document.getElementById("tab-d59d").style.backgroundColor = "#f0f0f0";
            document.getElementById("tab-a49b").style.backgroundColor = "#f0f0f0";
            document.getElementById("tab-7b6c").style.backgroundColor = "#f0f0f0";
            document.getElementById("tab-669a").style.backgroundColor = "#f0f0f0";
            document.getElementById("LayeringContainer01").style.backgroundColor = "#f0f0f0";
            
            document.getElementById("movieBoxSFPatch01").style.backgroundColor = "#f0f0f0";
            document.getElementById("movieBoxSFPatch01").style.borderColor = "#000000";
            document.getElementById("movieBoxSFPatch01").style.borderWidth = "1px";
            
            document.getElementById("ProofReadingContainer").style.backgroundColor = "#f0f0f0";
            document.getElementById("FavouriteContainer").style.backgroundColor = "#f0f0f0";
            document.getElementById("ExportContainer").style.backgroundColor = "#f0f0f0";
        
            //******************************************************************************
            
            //******************************************************************************
            //Handle element enabling/disabling
            
            //Indicate locking of new project panel
            document.getElementById("InstancePanelTitle").innerHTML = "Instance [Changes locked out]";
        
            //Project Panel
            document.getElementById("NewProjTabCh1FileDirInput").disabled = true; //Disable
            document.getElementById("NewProjTabCh1FileDirRefer").disabled = true; //Disable
            document.getElementById("NewProjTabCh2FileDirInput").disabled = true; //Disable
            document.getElementById("NewProjTabCh2FileDirRefer").disabled = true; //Disable
            document.getElementById("NewProjTabDataTypeDropDown").disabled = true; //Disable
            document.getElementById("NewProjTabTempResInput").disabled = true; //Disable
            document.getElementById("NewProjTabSpatialResInputInput").disabled = true; //Disable
            document.getElementById("NewProjTabExcludePixelsInput").disabled = true; //Disable
            document.getElementById("NewProjTabButtonCreate").disabled = true; //Disable
            document.getElementById("NewProjTabButtonLoadPreset").disabled = true; //Disable
            document.getElementById("LoadProjTabCh1FileDirInput").disabled = true; //Don't let user manually text edit this field
            document.getElementById("LoadProjTabFileDirRefer").disabled = true; //Disable
            document.getElementById("LoadProjTabButtonCreate").disabled = true; //Disable
            
            //DRL panel
            document.getElementById("DRLCellAdd").disabled = false; //Disable
            document.getElementById("DRLCellRemove").disabled = false; //Disable
            document.getElementById("DRLCellName").disabled = false; //Disable
            document.getElementById("DRLCellSave").disabled = false; //Disable
            document.getElementById("DRLCellLoad").disabled = false; //Disable						
            document.getElementById("DRLLdMarkAdd").disabled = false; //Disable
            document.getElementById("DRLLdMarkRemove").disabled = false; //Disable
            document.getElementById("DRLLdMarkName").disabled = false; //Disable
            document.getElementById("DRLLdMarkSave").disabled = false; //Disable
            document.getElementById("DRLLdMarkLoad").disabled = false; //Disable
            
            document.getElementById("UIR_DrawAnteriorButton").disabled = true; //Disable
            document.getElementById("UIR_updateFeaturesButton").disabled = true; //Disable
            document.getElementById("UIR_ExtractRIOButton").disabled = false; //Disable
            
            //Detect Panel
            DetectionTabSwitchingAllowed = false;
            document.getElementById("DetectPipeTab1RegistrateDropdown").disabled = true; //Disable
            document.getElementById("DetectPipeTab1PhotobleachDropdown").disabled = true; //Disable
            document.getElementById("DetectPipeTab1MedianFilterRadius").disabled = false; //Disable
            document.getElementById("DetectPipeTab1GaussianFilterRadius").disabled = false; //Disable
            document.getElementById("DetectPipeTab2IntensityThresInput").disabled = false; //Disable
            document.getElementById("DetectPipeTab2IntensityThresSlider").disabled = false; //Disable
            document.getElementById("DetectPipeTab2MinDurInput").disabled = false; //Disable
            document.getElementById("DetectPipeTab2MinSizeInput").disabled = false; //Disable
            document.getElementById("DetectPipeTab2MaxSizeInput").disabled = false; //Disable
            document.getElementById("DetectPipeTab2CircularityThresholdInput").disabled = false; //Disable
            //document.getElementById("DetectPipeTab2AllowedSameSignalDistance").disabled = false; //Disable
            //document.getElementById("DetectPipeTab2AdvancedFiltersToggle").disabled = true; //Disable
            document.getElementById("DetectPipeTab3EnableTempSegToggle").disabled = false; //Disable
            document.getElementById("DetectPipeTab3SeedSizeRelativeInput").disabled = false; //Disable
            document.getElementById("DetectPipeTab3ZscoreSigSeedInput").disabled = false; //Disable
            document.getElementById("DetectPipeTab3RiseTimeDiffThresInput").disabled = false; //Disable
            document.getElementById("DetectPipeTab3RefineTempAdjacentPeaks").disabled = false; //Disable
            document.getElementById("DetectPipeTab3GrowActiveRegionPerPatterns").disabled = false; //Disable
            document.getElementById("DetectPipeTab4TemporalExtensionToggle").disabled = false; //Disable
            document.getElementById("DetectPipeTab4EnableSpatialSegToggle").disabled = false; //Disable
            document.getElementById("DetectPipeTab4SourceSizeRatio").disabled = false; //Disable
            document.getElementById("DetectPipeTab4SourceDetectSensitivity").disabled = false; //Disable
            document.getElementById("DetectPipeTab5DetectGlobalSignalToggle").disabled = false; //Disable
            //document.getElementById("DetectPipeTab5GlobalSignalDuration").disabled = false; //Disable
            document.getElementById("DetectPipeTab6IgnoreDelayTauToggle").disabled = false; //Disable
            document.getElementById("DetectPipeTab6PropagationMetricRelativeToggle").disabled = false; //Disable
            document.getElementById("DetectPipeTab6NetworkFeaturesToggle").disabled = false; //Disable
            
            document.getElementById("DetectPipeBackButton").disabled = true; //Disable
            document.getElementById("DetectPipeRunButton").disabled = true; //Disable
            document.getElementById("DetectPipeNextButton").disabled = true; //Disable
            document.getElementById("DetectPipeSaveButton").disabled = true; //Disable
            document.getElementById("DetectPipeLoadButton").disabled = true; //Disable
            document.getElementById("DetectPipeRunAllStepsButton").disabled = false; //Disable
            
            //Layers Panel
            document.getElementById("LayersBrightnessSlider").disabled = false; //Disable
            document.getElementById("LayersBrightness2Slider").disabled = false; //Disable
            document.getElementById("dropDownFeatureOverlayType").disabled = false; //Disable
            document.getElementById("dropDownFeatureOverlayFeature").disabled = true; //Disable
            document.getElementById("dropDownFeatureOverlayColor").disabled = true; //Disable
            document.getElementById("UIR_updateOverlayButton").disabled = true; //Disable
            document.getElementById("leftViewPortChannel").disabled = true; //Disable
            document.getElementById("rightViewPortChannel").disabled = true; //Disable
            
            //Proof reading panel
            
            //Favourites panel
            document.getElementById("UIR_FavouritesTabSelectAllButton").disabled = true; //Disable
            document.getElementById("UIR_FavouritesTabDeleteButton").disabled = true; //Disable
            document.getElementById("UIR_FavouritesTabShowCurvesButton").disabled = true; //Disable
            document.getElementById("UIR_FavouritesTabSaveCurvesButton").disabled = true; //Disable
            document.getElementById("UIR_FavouritesTabSaveWavesButton").disabled = true; //Disable
            document.getElementById("UIR_toolsAddEvt1").disabled = true; //Disable
            document.getElementById("UIR_toolsAddEvt2").disabled = true; //Disable
            document.getElementById("UIR_toolsAddEvt1Button").disabled = true; //Disable
            document.getElementById("UIR_toolsAddEvt2Button").disabled = true; //Disable
            
            //Export tab
            document.getElementById("ExportTabProjectToggle").disabled = true; //Disable
            document.getElementById("ExportTabFeatureTableToggle").disabled = true; //Disable
            document.getElementById("ExportTabMovieWithOverlayToggle").disabled = true; //Disable
            document.getElementById("ExportTabExportButton").disabled = true; //Disable
            
            //Movie panel
            ViewportTabSwitchingAllowed = false;
            document.getElementById("MoviePanelJumpToInput").disabled = false; //Disable
            //Left and right movie type dropdowns are handled in SBS logic
            document.getElementById("MoviePanelSingleFrameLeftButton").disabled = false; //Disable
            document.getElementById("MoviePanelSingleFrameRightButton").disabled = false; //Disable
            document.getElementById("MoviePanelFrameSlider").disabled = false; //Disable
            document.getElementById("MoviePanelnEvtInput").disabled = true; //Disable
            
            //Downsampling Control Bar
            document.getElementById("Viewport1DSValue").disabled = false; //Disable
            document.getElementById("Viewport2LDSValue").disabled = false; //Disable
            document.getElementById("Viewport2RDSValue").disabled = false; //Disable
            document.getElementById("CurvePlotDSValue").disabled = false; //Disable
            document.getElementById("DSQualityButtonLow").disabled = false; //Disable
            document.getElementById("DSQualityButtonMed").disabled = false; //Disable
            document.getElementById("DSQualityButtonFull").disabled = false; //Disable
            
            //Clear the proofReading table and favourites Table
            document.getElementById("proofReadingTable-table").innerHTML = "";
            document.getElementById("favouriteTable-table").innerHTML = "";
            
            //******************************************************************************
            
            document.getElementById("NewProjTabCh1FileDirInput").value = data[5];
            document.getElementById("NewProjTabCh2FileDirInput").value = data[8];
            document.getElementById("NewProjTabDataTypeDropDown").selectedIndex = Number(data[11]) - 1;
            document.getElementById("NewProjTabTempResInput").value = data[14];
            document.getElementById("NewProjTabSpatialResInputInput").value = data[17];
            document.getElementById("NewProjTabExcludePixelsInput").value = data[20];
            
            //Update movie
            if (data[23] != "None")
            {
                viewportSingleIm1DataSrc = data[23];
                CanvasSingleViewportController(viewportSingleIm1DataSrc);
            }
            
            document.getElementById("DetectPipeTab1RegistrateDropdown").selectedIndex = Number(data[26]) - 1; //Sync data still
            document.getElementById("DetectPipeTab1PhotobleachDropdown").selectedIndex = Number(data[29]) - 1; //Sync data still
            
            document.getElementById("LayersMinSlider").disabled = false;
            document.getElementById("LayersMinSlider").min = "0";
            document.getElementById("LayersMinSlider").max = "1";
            document.getElementById("LayersMinSlider").value = String(Math.round(((parseFloat(data[32])) + Number.EPSILON) * 100) / 100);
            
            document.getElementById("LayersMaxSlider").disabled = false;
            document.getElementById("LayersMaxSlider").min = "0";
            document.getElementById("LayersMaxSlider").max = "1";
            document.getElementById("LayersMaxSlider").value = String(Math.round(((parseFloat(data[35])) + Number.EPSILON) * 100) / 100);
            
            
            document.getElementById("LayersBrightnessSlider").disabled = false;
            document.getElementById("LayersBrightnessSlider").min = "0";
            document.getElementById("LayersBrightnessSlider").max = "10";
            document.getElementById("LayersBrightnessSlider").value = String(Math.round(((parseFloat(data[38])) + Number.EPSILON) * 100) / 100);
            
            //{41} unused
            //{44} should be backend copy of {38}
            
            document.getElementById("LayersBrightness2Slider").disabled = false;
            document.getElementById("LayersBrightness2Slider").min = "0";
            document.getElementById("LayersBrightness2Slider").max = "10";
            document.getElementById("LayersBrightness2Slider").value = String(Math.round(((parseFloat(data[47])) + Number.EPSILON) * 100) / 100);
            
            //{50} unused
            //{53} unused
            
            document.getElementById("LayersColorBrightnessSlider").disabled = false;
            document.getElementById("LayersColorBrightnessSlider").min = "0";
            document.getElementById("LayersColorBrightnessSlider").max = "1";
            document.getElementById("LayersColorBrightnessSlider").value = String(Math.round(((parseFloat(data[56])) + Number.EPSILON) * 100) / 100);
            
            document.getElementById("MoviePanelFrameSlider").max = String(Math.round(((parseFloat(data[59])) + Number.EPSILON) * 1) / 1);
            document.getElementById("MoviePanelFrameSlider").value = String(Math.round(((parseFloat(data[62])) + Number.EPSILON) * 1) / 1);
            
            document.getElementById("MoviePanelJumpToInput").value = String(Math.round(((parseFloat(data[62])) + Number.EPSILON) * 1) / 1);
            
            movieCurrentFrame = parseInt(data[62]);
            movieTotalFrameCount = parseInt(data[59]);
            
            DetectPipelineMaximumTab = parseInt(data[65]);
            DetectPipelineCurrentTab = parseInt(data[68]);
            
            //Set the active tab
            $('.u-tabs-2').tabs({active: parseFloat(data[68]) - 1}); //This draws the correct tab. Switch 
            switch(parseInt(data[68]))
            {
                case 1:
                    document.getElementById("DetectPipePreProcessTab").classList.add('active');
                    document.getElementById("DetectPipeActiveTab").classList.remove('active');
                    document.getElementById("DetectPipeTemporalTab").classList.remove('active');
                    document.getElementById("DetectPipeSpatialTab").classList.remove('active');
                    document.getElementById("DetectPipeGlobalTab").classList.remove('active');
                    document.getElementById("DetectPipeFeatureTab").classList.remove('active');
                    break;
                case 2:
                    document.getElementById("DetectPipePreProcessTab").classList.remove('active');
                    document.getElementById("DetectPipeActiveTab").classList.add('active');
                    document.getElementById("DetectPipeTemporalTab").classList.remove('active');
                    document.getElementById("DetectPipeSpatialTab").classList.remove('active');
                    document.getElementById("DetectPipeGlobalTab").classList.remove('active');
                    document.getElementById("DetectPipeFeatureTab").classList.remove('active');
                    break;
                case 3:
                    document.getElementById("DetectPipePreProcessTab").classList.remove('active');
                    document.getElementById("DetectPipeActiveTab").classList.remove('active');
                    document.getElementById("DetectPipeTemporalTab").classList.add('active');
                    document.getElementById("DetectPipeSpatialTab").classList.remove('active');
                    document.getElementById("DetectPipeGlobalTab").classList.remove('active');
                    document.getElementById("DetectPipeFeatureTab").classList.remove('active');
                    break;
                case 4:
                    document.getElementById("DetectPipePreProcessTab").classList.remove('active');
                    document.getElementById("DetectPipeActiveTab").classList.remove('active');
                    document.getElementById("DetectPipeTemporalTab").classList.remove('active');
                    document.getElementById("DetectPipeSpatialTab").classList.add('active');
                    document.getElementById("DetectPipeGlobalTab").classList.remove('active');
                    document.getElementById("DetectPipeFeatureTab").classList.remove('active');
                    break;
                case 5:
                    document.getElementById("DetectPipePreProcessTab").classList.remove('active');
                    document.getElementById("DetectPipeActiveTab").classList.remove('active');
                    document.getElementById("DetectPipeTemporalTab").classList.remove('active');
                    document.getElementById("DetectPipeSpatialTab").classList.remove('active');
                    document.getElementById("DetectPipeGlobalTab").classList.add('active');
                    document.getElementById("DetectPipeFeatureTab").classList.remove('active');
                    break;
                case 6:
                    document.getElementById("DetectPipePreProcessTab").classList.remove('active');
                    document.getElementById("DetectPipeActiveTab").classList.remove('active');
                    document.getElementById("DetectPipeTemporalTab").classList.remove('active');
                    document.getElementById("DetectPipeSpatialTab").classList.remove('active');
                    document.getElementById("DetectPipeGlobalTab").classList.remove('active');
                    document.getElementById("DetectPipeFeatureTab").classList.add('active');
                    break;
            }
            
            //Sync back, run, next button enable/disable and labeling
            if ((data[71] == "off") || (data[74] == "off")) {
                document.getElementById("DetectPipeNextButton").disabled = true; document.getElementById("DetectPipeNextButton").style.visibility = "hidden"; DetectPipeNextButtonVisible = false; DetectPipeNextButtonDisabled = true;}
            else {document.getElementById("DetectPipeNextButton").disabled = false; document.getElementById("DetectPipeNextButton").style.visibility = "visible"; DetectPipeNextButtonVisible = true; DetectPipeNextButtonDisabled = false;}
            
            if (data[77] == "on") {document.getElementById("DetectPipeBackButton").style.visibility = "visible"; document.getElementById("DetectPipeBackButton").disabled = false; DetectPipeBackButtonVisible = true; DetectPipeBackButtonDisabled = false;}
            else {document.getElementById("DetectPipeBackButton").style.visibility = "hidden"; document.getElementById("DetectPipeBackButton").disabled = true; DetectPipeBackButtonVisible = false; DetectPipeBackButtonDisabled = true;}
            
            document.getElementById("DetectPipeRunButton").innerHTML = data[80];
            document.getElementById("DetectPipeRunButton").disabled = false;
            document.getElementById("DetectPipeNextButton").innerHTML = data[83];
            document.getElementById("DetectPipeSaveButton").disabled = false;
            document.getElementById("DetectPipeLoadButton").disabled = false;
            
            //Sync tabs visibilities
            if (data[86] == "on") {document.getElementById("DetectPipePreProcessTab").style.visibility='visible';} 
            else {document.getElementById("DetectPipePreProcessTab").style.visibility='hidden';}
            
            if (data[89] == "on") {document.getElementById("DetectPipeActiveTab").style.visibility='visible';} 
            else {document.getElementById("DetectPipeActiveTab").style.visibility='hidden';}
            
            if (data[92] == "on") {document.getElementById("DetectPipeTemporalTab").style.visibility='visible';} 
            else {document.getElementById("DetectPipeTemporalTab").style.visibility='hidden';}
            
            if (data[95] == "on") {document.getElementById("DetectPipeSpatialTab").style.visibility='visible';} 
            else {document.getElementById("DetectPipeSpatialTab").style.visibility='hidden';}
            
            if (data[98] == "on") {document.getElementById("DetectPipeGlobalTab").style.visibility='visible';} 
            else {document.getElementById("DetectPipeGlobalTab").style.visibility='hidden';}
            
            if (data[101] == "on") {document.getElementById("DetectPipeFeatureTab").style.visibility='visible';} 
            else {document.getElementById("DetectPipeFeatureTab").style.visibility='hidden';}
            
            //Active
            document.getElementById("DetectPipeTab1GaussianFilterRadius").value = data[104]; //Sync data still
            //document.getElementById("DetectPipeTab2NoiseEstDropdown").selectedIndex = Number(data[107]) - 1; //Sync data still
            document.getElementById("DetectPipeTab2IntensityThresInput").value = data[110]; //Sync data still
            document.getElementById("DetectPipeTab2MinDurInput").value = data[113]; //Sync data still
            document.getElementById("DetectPipeTab2MinSizeInput").value = data[116]; //Sync data still
            document.getElementById("DetectPipeTab2MaxSizeInput").value = data[119]; //Sync data still
            document.getElementById("DetectPipeTab2CircularityThresholdInput").value = data[122]; //Sync data still
            //Temporal
            if (data[125] == "true"){document.getElementById("DetectPipeTab3EnableTempSegToggle").checked = true;}else{document.getElementById("DetectPipeTab3EnableTempSegToggle").checked = false;}
            document.getElementById("DetectPipeTab3SeedSizeRelativeInput").value = data[284] //Sync data still
            document.getElementById("DetectPipeTab3ZscoreSigSeedInput").value = data[128] //Sync data still
            document.getElementById("DetectPipeTab3RiseTimeDiffThresInput").value = data[131]; //Sync data still
            if (data[134] == "true"){document.getElementById("DetectPipeTab3RefineTempAdjacentPeaks").checked = true;}else{document.getElementById("DetectPipeTab3RefineTempAdjacentPeaks").checked = false;}
            if (data[137] == "true"){document.getElementById("DetectPipeTab3GrowActiveRegionPerPatterns").checked = true;}else{document.getElementById("DetectPipeTab3GrowActiveRegionPerPatterns").checked = false;}
            if (data[329] == "true"){document.getElementById("DetectPipeTab4TemporalExtensionToggle").checked = true;}else{document.getElementById("DetectPipeTab4TemporalExtensionToggle").checked = false;}
            //Spatial
            if (data[140] == "true"){document.getElementById("DetectPipeTab4EnableSpatialSegToggle").checked = true;}else{document.getElementById("DetectPipeTab4EnableSpatialSegToggle").checked = false;}
            document.getElementById("DetectPipeTab4SourceSizeRatio").value = data[143];
            document.getElementById("DetectPipeTab4SourceDetectSensitivity").value = data[146];
            //Global
            if (data[152] == "true"){document.getElementById("DetectPipeTab5DetectGlobalSignalToggle").checked = true;}else{document.getElementById("DetectPipeTab5DetectGlobalSignalToggle").checked = false;}
            //Feature
            if (data[155] == "true"){document.getElementById("DetectPipeTab6IgnoreDelayTauToggle").checked = true;}else{document.getElementById("DetectPipeTab6IgnoreDelayTauToggle").checked = false;}
            if (data[287] == "true"){document.getElementById("DetectPipeTab6PropagationMetricRelativeToggle").checked = true;}else{document.getElementById("DetectPipeTab6PropagationMetricRelativeToggle").checked = false;}
            if (data[290] == "true"){document.getElementById("DetectPipeTab6NetworkFeaturesToggle").checked = true;}else{document.getElementById("DetectPipeTab6NetworkFeaturesToggle").checked = false;}

            if (data[158] == "off") {document.getElementById("DetectPipeTab1RegistrateDropdown").disabled = true;} else {document.getElementById("DetectPipeTab1RegistrateDropdown").disabled = false;}
            if (data[161] == "off") {document.getElementById("DetectPipeTab1PhotobleachDropdown").disabled = true;} else {document.getElementById("DetectPipeTab1PhotobleachDropdown").disabled = false;}
            
            //We skip {86}
            //{89}
            //{92}
            //{95}
            //{98}
            //{101}
            
            //Export
            if (data[164] == "true"){document.getElementById("ExportTabProjectToggle").checked = true;}else{document.getElementById("ExportTabProjectToggle").checked = false;}
            if (data[167] == "true"){document.getElementById("ExportTabFeatureTableToggle").checked = true;}else{document.getElementById("ExportTabFeatureTableToggle").checked = false;}
            if (data[170] == "true"){document.getElementById("ExportTabMovieWithOverlayToggle").checked = true;}else{document.getElementById("ExportTabMovieWithOverlayToggle").checked = false;}
            //if (data[173] == "1"){document.getElementById("ExportTabEventsSmallToggle").checked = true;}else{document.getElementById("ExportTabEventsSmallToggle").checked = false;}
            
            
            if (data[176] == "off")
            {
                document.getElementById("ExportContainer").style.backgroundColor = "#ffc7c7";
                document.getElementById("ExportTabProjectToggle").disabled = true;
                document.getElementById("ExportTabExportButton").disabled = true;
                document.getElementById("ExportTabFeatureTableToggle").disabled = true;
                document.getElementById("ExportTabMovieWithOverlayToggle").disabled = true;
                
            }
            else
            {
                document.getElementById("ExportContainer").style.backgroundColor = "#f0f0f0";
                document.getElementById("ExportTabProjectToggle").disabled = false;
                document.getElementById("ExportTabExportButton").disabled = false;
                document.getElementById("ExportTabFeatureTableToggle").disabled = false;
                document.getElementById("ExportTabMovieWithOverlayToggle").disabled = false;
            }
            
            document.getElementById("DetectPipeTab1MedianFilterRadius").value = data[188]; //Sync data still
            document.getElementById("DetectPipeTab2IntensityThresSlider").value = data[191]; //Sync data still
            document.getElementById("DetectPipeTab2IntensityThresSlider").max = data[194]; //Sync data still
            //document.getElementById("DetectPipeTab2AllowedSameSignalDistance").value = data[197];
            //document.getElementById("DetectPipeTab5GlobalSignalDuration").value = data[200];
            
            if (data[203] == "on") {document.getElementById("DetectPipeRunButton").disabled = false;} else {document.getElementById("DetectPipeRunButton").disabled = true;}
            if (data[206] == "on") {document.getElementById("DetectPipeRunButton").style.visibility = "visible";} else {document.getElementById("DetectPipeRunButton").style.visibility = "hidden";}
            if (data[209] == "on") {document.getElementById("DetectPipeBackButton").disabled = false;} else {document.getElementById("DetectPipeBackButton").disabled = true;}

            if (data[212] == "true")
            {
                AQuA_SideBySideMode = true;
                viewportCurrentTab = 2;
                $('.u-tabs-3').tabs({active: 1}); //This draws the correct tab. Switch 
                document.getElementById("SingleViewPortTab").classList.remove('active');
                document.getElementById("DualViewPortTab").classList.add('active');
            }
            else
            {
                AQuA_SideBySideMode = false;
                viewportCurrentTab = 1;
                $('.u-tabs-3').tabs({active: 0}); //This draws the correct tab. Switch 
                document.getElementById("DualViewPortTab").classList.remove('active');
                document.getElementById("SingleViewPortTab").classList.add('active');
            }
            
            
            //Need to prepare dropDownFeatureOverlayType before setting its selection value
            removeOptions(document.getElementById("dropDownFeatureOverlayType"));
            if (DetectPipelineMaximumTab >= 5){document.getElementById("dropDownFeatureOverlayType").add(new Option("Events", 1));}
            if (DetectPipelineMaximumTab >= 0){document.getElementById("dropDownFeatureOverlayType").add(new Option("None", 1));}
            if (DetectPipelineMaximumTab >= 3){document.getElementById("dropDownFeatureOverlayType").add(new Option("Step 2: active voxels", 1));}
            if (DetectPipelineMaximumTab >= 3){document.getElementById("dropDownFeatureOverlayType").add(new Option("Step 3a: watershed results", 1));}
            if (DetectPipelineMaximumTab >= 3){document.getElementById("dropDownFeatureOverlayType").add(new Option("Step 3aa: seeds", 1));}
            if (DetectPipelineMaximumTab >= 4){document.getElementById("dropDownFeatureOverlayType").add(new Option("Step 3b: super events", 1));}
            
            document.getElementById("dropDownFeatureOverlayType").selectedIndex = Number(data[215]) - 1;
            document.getElementById("dropDownFeatureOverlayFeature").selectedIndex = Number(data[218]) - 1;
            document.getElementById("dropDownFeatureOverlayColor").selectedIndex = Number(data[221]) - 1;
            document.getElementById("leftViewPortChannel").selectedIndex = Number(data[317]) - 1;
            document.getElementById("rightViewPortChannel").selectedIndex = Number(data[320]) - 1;
            
            document.getElementById("dropDownFeatureOverlayFeature").selectedIndex = Number(data[218]) - 1;
            document.getElementById("dropDownFeatureOverlayColor").selectedIndex = Number(data[221]) - 1;
            
            if (data[224] == "on") {document.getElementById("UIR_updateOverlayButton").disabled = false;} else {document.getElementById("UIR_updateOverlayButton").disabled = true;}
            if (data[227] == "on") {document.getElementById("UIR_updateFeaturesButton").disabled = false;} else {document.getElementById("UIR_updateFeaturesButton").disabled = true;}
            
            document.getElementById("MoviePanelnEvtInput").value = data[230];
            document.getElementById("UIR_toolsAddEvt1").value = data[233];
            document.getElementById("UIR_toolsAddEvt2").value = data[236];
            
            //Build favourites table
            favouritesTableConstructor(data[239]);
            
            document.getElementById("movLTypeDropdown").selectedIndex = Number(data[242]) - 1;
            document.getElementById("movRTypeDropdown").selectedIndex = Number(data[245]) - 1;
            
            proofReadingTableConstructor(data[248]);
            
            //document.getElementById("graphDisplay").src= data[254];
            //document.getElementById("graphDisplay").style.maxHeight = "280px"; 
            CurveViewportController(data[254],data[311],data[314]);
            
            if (data[251] != "None")
            {
                //console.log("Loading dual viewport controller in FrontEndUIUpdate.js");
                CanvasDualViewportControllerLoadQueue(data[251],data[263]);
            }
            
            //Update movie
            if (data[254] != "None")
            {
                curvePortImData = data[254];
                CurveViewportController(curvePortImData);
            }
            
            if (!(AQuA_SideBySideMode))
            {
                //This slider has dual functionality. It is either sldBri2 or sldBriR on the backend depending on whether side-by-side view is enabled
                document.getElementById("LayersBrightness2Slider").disabled = true;
                document.getElementById("LayersBrightnessSliderTitle").innerHTML = "Intensity Brightness";
                document.getElementById("LayersBrightness2SliderTitle").innerHTML = "[Disabled]";
                document.getElementById("LayersBrightness2Slider").min = "0.1";
                document.getElementById("LayersBrightness2Slider").max = "10";
                document.getElementById("LayersBrightness2Slider").value = String(Math.round(((parseFloat(data[47])) + Number.EPSILON) * 100) / 100);
                document.getElementById("movLTypeDropdown").disabled = true; //Disable
                document.getElementById("movRTypeDropdown").disabled = true; //Disable
            }
            else
            {
                //This slider has dual functionality. It is either sldBri2 or sldBriR on the backend depending on whether side-by-side view is enabled
                document.getElementById("LayersBrightness2Slider").disabled = false;
                document.getElementById("LayersBrightnessSliderTitle").innerHTML = "Left Brightness";
                document.getElementById("LayersBrightness2SliderTitle").innerHTML = "Right Brightness";
                document.getElementById("LayersBrightness2Slider").min = "0.1";
                document.getElementById("LayersBrightness2Slider").max = "10";
                document.getElementById("LayersBrightness2Slider").value = String(Math.round(((parseFloat(data[47])) + Number.EPSILON) * 100) / 100);
                document.getElementById("movLTypeDropdown").disabled = false; //Disable
                document.getElementById("movRTypeDropdown").disabled = false; //Disable
            }
            
            
            if (data[257] == "off") //User is forced into SBS mode due to dual channel mode
            {
                document.getElementById("SingleViewPortTab").style.visibility='hidden';
                sbsLockedIn = true; //For the viewport select tab response function to deny switching to single viewport if locked in is true
            }
            
            if (data[260] == "on") {document.getElementById("MoviePanelGaussFilterButton").disabled = false;} else {document.getElementById("MoviePanelGaussFilterButton").disabled = true;}
            
            if (data[266] == "on") {document.getElementById("dropDownFeatureOverlayFeature").disabled = false;} else {document.getElementById("dropDownFeatureOverlayFeature").disabled = true;}

            document.getElementById("Viewport1DSValue").value = data[269];
            document.getElementById("Viewport2LDSValue").value = data[272];
            document.getElementById("Viewport2RDSValue").value = data[275];
            document.getElementById("CurvePlotDSValue").value = data[278];


            document.getElementById("DetectPipeTab3SeedSizeRelativeInput").value = data[284];

            if (data[287] == "true"){document.getElementById("DetectPipeTab6PropagationMetricRelativeToggle").checked = true;}else{document.getElementById("DetectPipeTab6PropagationMetricRelativeToggle").checked = false;}
            if (data[290] == "true"){document.getElementById("DetectPipeTab6NetworkFeaturesToggle").checked = true;}else{document.getElementById("DetectPipeTab6NetworkFeaturesToggle").checked = false;}


            if (data[293] == "off")
            {
                document.getElementById("ProofReadingContainer").style.backgroundColor = "#ffc7c7";
                document.getElementById("UIR_ProofReadingViewFavButton").disabled = true; //Disable
                document.getElementById("UIR_ProofReadingDeleteRestoreButton").disabled = true; //Disable
                document.getElementById("UIR_ProofReadingAddAllFilteredButton").disabled = true; //Disable
                document.getElementById("UIR_ProofReadingFeaturesPlotButton").disabled = true; //Disable
            }
            else
            {
                document.getElementById("ProofReadingContainer").style.backgroundColor = "#f0f0f0";
                document.getElementById("UIR_ProofReadingViewFavButton").disabled = false; //Enable
                document.getElementById("UIR_ProofReadingDeleteRestoreButton").disabled = false; //Enable
                document.getElementById("UIR_ProofReadingAddAllFilteredButton").disabled = false; //Enable
                document.getElementById("UIR_ProofReadingFeaturesPlotButton").disabled = false; //Enable
            }
            
            if (data[296] == "off")
            {
                document.getElementById("FavouriteContainer").style.backgroundColor = "#ffc7c7";
                document.getElementById("UIR_FavouritesTabSelectAllButton").disabled = true; //Disable
                document.getElementById("UIR_FavouritesTabDeleteButton").disabled = true; //Disable
                document.getElementById("UIR_FavouritesTabShowCurvesButton").disabled = true; //Disable
                document.getElementById("UIR_FavouritesTabSaveCurvesButton").disabled = true; //Disable
                document.getElementById("UIR_FavouritesTabSaveWavesButton").disabled = true; //Disable
                document.getElementById("UIR_toolsAddEvt1").disabled = true; //Disable
                document.getElementById("UIR_toolsAddEvt2").disabled = true; //Disable
                document.getElementById("UIR_toolsAddEvt1Button").disabled = true; //Disable
                document.getElementById("UIR_toolsAddEvt2Button").disabled = true; //Disable
            }
            else
            {
                document.getElementById("FavouriteContainer").style.backgroundColor = "#f0f0f0";
                document.getElementById("UIR_FavouritesTabSelectAllButton").disabled = false; //Disable
                document.getElementById("UIR_FavouritesTabDeleteButton").disabled = false; //Disable
                document.getElementById("UIR_FavouritesTabShowCurvesButton").disabled = false; //Disable
                document.getElementById("UIR_FavouritesTabSaveCurvesButton").disabled = false; //Disable
                document.getElementById("UIR_FavouritesTabSaveWavesButton").disabled = false; //Disable
                document.getElementById("UIR_toolsAddEvt1").disabled = false; //Disable
                document.getElementById("UIR_toolsAddEvt2").disabled = false; //Disable
                document.getElementById("UIR_toolsAddEvt1Button").disabled = false; //Disable
                document.getElementById("UIR_toolsAddEvt2Button").disabled = false; //Disable
            }
            
            if (data[299] == "on") {document.getElementById("dropDownFeatureOverlayColor").disabled = false;} else {document.getElementById("dropDownFeatureOverlayColor").disabled = true;}
            if (data[323] == "on") {document.getElementById("leftViewPortChannel").disabled = false;} else {document.getElementById("leftViewPortChannel").disabled = true;}
            if (data[326] == "on") {document.getElementById("rightViewPortChannel").disabled = false;} else {document.getElementById("rightViewPortChannel").disabled = true;}
            currentFrame = parseInt(data[302]);
            maximumFrame = parseInt(data[305]);
            var temp = ['Frame ',data[302], '/', data[305]];
            document.getElementById("MoviePanelCurrentFrameIndicator").innerHTML = temp.join("");

            if (Number(data[308]) == 0){
                document.getElementById("MoviePanelGaussFilterButton").style.setProperty("color","black","important");
                document.getElementById("MoviePanelGaussFilterButton").style.setProperty("border","1px solid black","important");
            }else{
                document.getElementById("MoviePanelGaussFilterButton").style.setProperty("color","green","important");
                document.getElementById("MoviePanelGaussFilterButton").style.setProperty("border","1px solid green","important");
            }
            break;
        default:
            break;
    }
    return project_state;
}