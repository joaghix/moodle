YUI.add("moodle-atto_fontsize-button",function(e,t){var n=[{name:"12px"},{name:"14px"},{name:"16px"},{name:"18px"},{name:"20px"},{name:"24px"},{name:"26px"},{name:"30px"},{name:"32px"},{name:"36px"}];e.namespace("M.atto_fontsize").Button=e.Base.create("button",e.M.editor_atto.EditorPlugin,[],{initializer:function(){var t=[];e.Array.each(n,function(e){t.push({text:'<span style="font-size:'+e.name+';">'+e.name+"</span>",callbackArgs:e.name,callback:this._changeStyle})}),this.addToolbarMenu({globalItemConfig:{callback:this._changeStyle},icon:"icon",iconComponent:"atto_fontsize",items:t})},_changeStyle:function(e,t){this.get("host").formatSelectionInlineStyle({fontSize:t})}})},"@VERSION@");




