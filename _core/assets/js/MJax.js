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

                var objFormData = MJax.Serialize(MJax.FormData);


                MJax.Connections['ajax'].TriggerEvent(objFormData);
            }
        },
        Connections:{},
        ControlDefinitions:{},

        Init:function(objFormData){
            MJax.FormData = objFormData;

            MJax.InitRequire();
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
                ['jquery', 'mustache','MJaxAjaxConn'],
                function($,  Mustache) {
                    window.Mustache = Mustache;
                    MJax.Connections['ajax'] = new MJaxAjaxConn();
                    requirejs(MJax.FormData.head.require_modules, function(){


                        MJax.InitControlDefinitions();
                    });
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
            $(document).trigger('mjax-run-start');
            MJax.FormData = MJax.Unserialize(MJax.FormData);
            MJax.Render();
            MJax.FormData.AttachControls();
        },
        Render:function(){
            $('body').html(
                MJax.FormData.Render()
            );

        },
        Update:function(objJson){
            for(var strControlId in objJson.body){
                MJax.FormData.body[strControlId].Update(objJson.body[strControlId]);
            }
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
            if(typeof(mixData) == 'undefined'){
                return undefined;
            }
            if(typeof(mixData.MSerialize_before) != 'undefined'){
                mixData.MSerialize_before();
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
                if(typeof(mixData._mclass) != 'undefined'){
                    if(typeof(mixData._m_clientside_class) != 'undefined'){
                        var funCtl = MJax.ControlDefinitions[mixData._m_clientside_class];
                    }else{
                        var funCtl = MJax.ControlDefinitions[mixData._mclass];
                    }
                    if(typeof(funCtl) != 'undefined'){
                        var objReturn = new funCtl(mixData);
                    }else{
                        MJax.Log("Missing Javascript MClass Definition: "+ mixData._mclass + ' or ' + mixData._m_clientside_class);
                    }
                }else{
                    var objReturn = {};
                    for(var strKey in mixData){
                        var mixSubData = MJax.Unserialize(mixData[strKey]);
                        if(typeof(mixSubData) != 'undefined'){
                            objReturn[strKey] = mixSubData;
                        }
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
