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
                for(var strControlId in MJax.Controls){
                    objData[strControlId] = MJax.Serialize(MJax.Controls[strControlId]);
                }

                MJax.Connections['ajax'].TriggerEvent(objData);
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
            for(var strId in MJax.FormData.body){
                var objControl = MJax.FormData.body[strId];
                var funCtl = MJax.ControlDefinitions[objControl.Type];
                MJax.Controls[objControl.ControlId] = new funCtl(objControl);

            }
            MJax.Render();
            for(var strId in MJax.Controls){
                MJax.Controls[strId].Attach();
            }
        },
        Render:function(){
            MJax.LoadTemplate();
            var strBody = window.Mustache.render(
                MJax.FormData.head.template_html,
                this
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
            var strJType = typeof(mixData);
            if(strJType == 'object'){
                if(typeof(mixData['MSerialize']) != 'undefined'){
                    return mixData.MSerialize();
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
        TriggerEvent:function(objEvent){

        }

    };
})(window);
