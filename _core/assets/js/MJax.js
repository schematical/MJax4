(function(){
    window.MJax = MJax = {
        Actions:{
            'MJaxServerControlAction':function(objEvent){
                var objControl = $(this).data('mjax-control');
                //Figure out a priority thing
                var objData = {};
                objData.action = 'control_event';
                objData.control_id = objControl.ControlId;
                objData.event = objEvent.type;
                //objData.MJaxForm__FormState = strFormState;
                if(objEvent.type == 'keypress'){
                    objData.keyCode = objEvent.keyCode;
                }
                MJax.FormData.head.event = objData;//Consider makng this an array that can be pushed and popped
                for(var strControlId in MJax.Controls){
                    MJax.FormData.body[strControlId] = MJax.Serialize(MJax.Controls[strControlId]);
                }

                MJax.Connections['ajax'].TriggerEvent(MJax.FormData);
            }
        },
        Connections:{},
        ControlDefinitions:{},
        Controls:{},
        Init:function(objFormData){
            MJax.FormData = objFormData;

            MJax.InitRequire();


            //MJax.Run();

        },
        LoadTemplate:function(){
            /*require(
                ['text!' + MJax.FormData.head.template],
                function(html) {
                    MJax.FormData.head.template_html = html;
                }
            );*/
        },
        InitRequire:function(){
            require.config(
                MJax.FormData.head.require
            );
            requirejs(
                ['jquery','jquery_mobile', 'mustache','MJaxAjaxConn'],//, 'require_text'],
                function($, jMobile, Mustache) {
                    window.Mustache = Mustache;
                    MJax.Connections['ajax'] = new MJaxAjaxConn();
                    MJax.InitControlDefinitions();
                }
            );
        },
        InitControlDefinitions:function(){
            requirejs(
                MJax.FormData.head.controls,
                function() {
                    MJax.Run();
                }
            );
        },
        Run:function(){
            MJax.FormData = MJax.Unserialize(MJax.FormData);
            MJax.Render();
            for(var strId in MJax.Controls){
                MJax.Controls[strId].Attach();
            }
        },
        Render:function(){
            MJax.LoadTemplate();
            var objRenderData = MJax.FormData.body;
            objRenderData['_head'] = MJax.FormData.head;
            var strBody = window.Mustache.render(
                MJax.FormData.head.template_html,
                objRenderData
            );
            $('body').html(
                strBody
            );
        },
        Update:function(objJson){

        },
        Error:function(objJson){

        },
        Serialize:function(mixData){
            if(mixData == null){
                return null;
            }
            if(mixData instanceof jQuery){
                return undefined;
            }
            var strJType = typeof(mixData);
            if(strJType == 'object'){
                if(typeof(mixData['_MSerialize']) != 'undefined'){
                    return mixData._MSerialize();
                }
                var objReturn = {};
                for(var strKey in mixData){
                    var mixChildData  = MJax.Serialize(mixData[strKey]);
                    if(typeof(mixChildData) != 'undefined'){
                        objReturn[strKey] = mixChildData;
                    }
                }
            }else if(strJType == 'array'){

                var objReturn = [];
                for(var strKey in mixData){
                    var mixChildData  = MJax.Serialize(mixData[strKey]);
                    if(typeof(mixChildData) != 'undefined'){
                        objReturn[strKey] = mixChildData;
                    }
                }
            }else if(
                //Put types to ignore here
                (strJType == 'function')
            ){
                //Do nothing
            }else{
                var objReturn = mixData;
            }
            return objReturn;
        },
        Unserialize:function(mixData){

            if(typeof mixData == 'object'){
                if(typeof(mixData._mclass != 'undefined')){
                    MJax.Log("Unserializing: " + mixData._mclass);
                    var funCtl = MJax.ControlDefinitions[mixData._mclass];
                    if(typeof(funCtl) != 'undefined'){
                        var objReturn = new funCtl(mixData);
                    }else{
                        MJax.Log("Missing Javascript MClass Definition: "+ mixData._mclass);
                    }

                }else{
                    var objReturn = {};
                    for(var strId in mixData){
                        objReturn[strId] = MJax.Unserialize(mixData[strId]);
                    }
                }
            }else{
                objReturn = mixData;
            }
            return objReturn;
        },
        TriggerEvent:function(objEvent){

        },
        Log:function(mixLog){
            //TODO: Write this
            console.log(mixLog);
        }

    };
})(window);
MJax.Init(MJax_Data);
