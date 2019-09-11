<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml><![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
</head>
<body style="width: 100% !important;min-width: 100%;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100% !important;margin: 0;padding: 0;background-color: #FFFFFF">
<div bgcolor="#dddddd" marginheight="0" marginwidth="0"
     style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:14px;line-height:1.42857143;color:#333333;background-color:#ddd;margin:0;padding:0;width:100%!important">
    <table bgcolor="#dddddd" border="0" cellpadding="0" cellspacing="0" class="m_5500548108047303379wrapper"
           width="100%"
           style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;color:#454545;border-collapse:collapse;margin:0;padding:0;table-layout:fixed;width:100%;line-height:100%">
        <tbody>
        <tr style="box-sizing:border-box;border-collapse:collapse">
            <td style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;color:#454545;border-collapse:collapse">
                <br style="box-sizing:border-box">
                <table align="center" bgcolor="#ffffff" border="1" cellpadding="0" cellspacing="0"
                       class="m_5500548108047303379section m_5500548108047303379w600" frame="border" rules="none"
                       width="600"
                       style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;color:#454545;border:1px solid #c4c4c4;border-spacing:0px;border-collapse:collapse;background-color:#fff;width:600px!important">
                    <tbody>
                    <tr style="box-sizing:border-box;border-collapse:collapse">
                        <td style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                   class="m_5500548108047303379section-alert m_5500548108047303379alert-error m_5500548108047303379w600"
                                   style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;border-width:1px 0 1px 0;border-style:solid;color:#666;margin-bottom:20px;font-size:14px;background-color:#ffecce;border-color:#e1b08e;width:600px!important;border-top-width:0"
                                   width="600">
                                <tbody>
                                <tr style="box-sizing:border-box;border-collapse:collapse">
                                    <td style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;padding-top:14px;padding-bottom:14px;color:#cc603d">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0"
                                               class="m_5500548108047303379container m_5500548108047303379w520"
                                               width="520"
                                               style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d;width:520px!important">
                                            <tbody>
                                            <tr style="box-sizing:border-box;border-collapse:collapse">
                                                <td valign="top"
                                                    style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                    <h2 style="box-sizing:border-box;font-family:inherit;font-weight:500;color:inherit;margin-top:20px;margin-bottom:10px;font-size:24px;line-height:36px">
                                                        @if($type == 'renewal')
                                                            Напомняне за просрочено подновяване
                                                            @else
                                                            Напомняне за просрочено обслужване
                                                            @endif

                                                    </h2>
                                                </td>


                                            </tr>
                                            </tbody>
                                        </table>
                                        <table align="center" border="0" cellpadding="5" cellspacing="0"
                                               class="m_5500548108047303379container m_5500548108047303379w520"
                                               width="520"
                                               style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d;width:520px!important">
                                            <tbody>
                                            <tr style="box-sizing:border-box;border-collapse:collapse">
                                                <td class="m_5500548108047303379w40" valign="top" width="40"
                                                    style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                    <img alt="{{$vehicle->name}}" height="40"
                                                         src="{{env('APP_URL').'/'.(isset($vehicle->pic)?$vehicle->pic:'img/car.png')}}"
                                                         width="40"
                                                         style="box-sizing:border-box;vertical-align:middle;outline:none;text-decoration:none;display:block;height:auto;line-height:100%"
                                                         >

                                                </td>
                                                <td valign="top"
                                                    style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                    <table style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                        <tbody>
                                                        <tr style="box-sizing:border-box;border-collapse:collapse">
                                                            <td class="m_5500548108047303379w130" valign="top"
                                                                width="130"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                                <span class="m_5500548108047303379muted"
                                                                      style="box-sizing:border-box">{{ucfirst(__('labels.vehicle'))}}:</span>

                                                            </td>
                                                            <td valign="top"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                                <strong style="box-sizing:border-box"><a
                                                                            href="{{env('APP_URL').'/#/'.$vehicle->company_id.'/vehicles/'.$vehicle->id}}"
                                                                            style="box-sizing:border-box;color:#337ab7;text-decoration:underline"
                                                                            target="_blank"
                                                                    >
                                                                        {{$vehicle->name}}</a></strong>

                                                            </td>
                                                        </tr>
                                                        <tr style="box-sizing:border-box;border-collapse:collapse">
                                                            <td class="m_5500548108047303379w130" valign="top"
                                                                width="130"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                                <span class="m_5500548108047303379muted"
                                                                      style="box-sizing:border-box">{{ucfirst(__('labels.license_plate'))}}:</span>

                                                            </td>
                                                            <td valign="top"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                                <strong style="box-sizing:border-box">
                                                                        {{$vehicle->plate}}</strong>

                                                            </td>
                                                        </tr>
                                                        <tr style="box-sizing:border-box;border-collapse:collapse">
                                                            <td class="m_5500548108047303379w130" valign="top"
                                                                width="130"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                                <span class="m_5500548108047303379muted"
                                                                      style="box-sizing:border-box">{{__('labels.'.$type.'_task')}}
                                                                    :</span>

                                                            </td>
                                                            <td valign="top"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                                <strong style="box-sizing:border-box">{{__('labels.'.$type.'_types.'.$task->name)}}</strong>

                                                            </td>
                                                        </tr>
                                                        <tr style="box-sizing:border-box;border-collapse:collapse">
                                                            <td class="m_5500548108047303379w130" valign="top"
                                                                width="130"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                                <span class="m_5500548108047303379muted"
                                                                      style="box-sizing:border-box">Група:</span>

                                                            </td>
                                                            <td valign="top"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                                <strong style="box-sizing:border-box">{{$group?$group->name:''}}</strong>

                                                            </td>
                                                        </tr>
                                                        <tr style="box-sizing:border-box;border-collapse:collapse">
                                                            <td class="m_5500548108047303379w130" valign="top"
                                                                width="130"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                                <span class="m_5500548108047303379muted"
                                                                      style="box-sizing:border-box">{{ucfirst(__('labels.driver_or_operator'))}}:</span>

                                                            </td>
                                                            <td valign="top"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#cc603d">
                                                                <strong style="box-sizing:border-box">{{$operatorName}}</strong>

                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                </td>


                                            </tr>
                                            </tbody>
                                        </table>

                                    </td>

                                </tr>
                                </tbody>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                   class="m_5500548108047303379container m_5500548108047303379w520" width="520"
                                   style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px;width:520px!important">
                                <tbody>
                                <tr style="box-sizing:border-box;border-collapse:collapse">
                                    <td class="m_5500548108047303379w280" valign="top" width="280"
                                        style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                        <h5 style="box-sizing:border-box;font-family:inherit;color:inherit;margin-top:10px;margin-bottom:10px;font-size:14px;line-height:20px;font-weight:bold">
                                            Изтича на
                                        </h5>
                                        <p style="box-sizing:border-box;margin:0 0 10px;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;font-size:14px">
                                            @if(strpos($trigger_type, 'noticeTime') !== false)
                                                Дата
                                            @else
                                                Километраж
                                            @endif
                                            <strong style="box-sizing:border-box">{{$trigger}}</strong>
                                            <small class="m_5500548108047303379text-error"
                                                   style="box-sizing:border-box;font-size:85%;color:#e3302c">
                                                ( преди {{$difference}}
                                                @if(strpos($trigger_type, 'noticeTime') !== false)
                                                    {{__('labels.days')}}
                                                @else
                                                    km
                                                @endif

                                                )
                                            </small>
                                        </p>

                                    </td>
                                    <td valign="top" width="20"
                                        style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px"></td>

                                    <td class="m_5500548108047303379w220" valign="top" width="220"
                                        style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                        <h5 style="box-sizing:border-box;font-family:inherit;color:inherit;margin-top:10px;margin-bottom:10px;font-size:14px;line-height:20px;font-weight:bold">
                                            Настройки за напомняне
                                            <small style="box-sizing:border-box;font-weight:normal;line-height:1;color:#777777;font-size:75%">
                                                (<a href="{{env('APP_URL').'/#/'.$vehicle->company_id.'/'.$type.'_reminders/'.$reminder->id.'/edit'}}"
                                                    style="box-sizing:border-box;color:#337ab7;text-decoration:underline"
                                                    target="_blank"
                                                >редактирай</a>)
                                            </small>
                                        </h5>
                                        <p class="m_5500548108047303379muted"
                                           style="box-sizing:border-box;margin:0 0 10px;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;font-size:14px">
                                            @if($reminder->odometer_interval)
                                                На всеки {{number_format($reminder->odometer_interval, 0)}} километра
                                            @endif

                                            @if($reminder->time_interval)
                                                    На всеки {{$reminder->time_interval}} {{__('labels.'.$reminder->time_interval_unit)}}
                                            @endif


                                        </p>

                                    </td>


                                </tr>
                                </tbody>
                            </table>
                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                   class="m_5500548108047303379section-alert m_5500548108047303379alert-success m_5500548108047303379w600"
                                   width="600"
                                   style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;border-width:1px 0 1px 0;border-style:solid;color:#666;margin-bottom:20px;font-size:14px;background-color:#edfbd8;border-color:#bfde84;width:600px!important">
                                <tbody>
                                <tr style="box-sizing:border-box;border-collapse:collapse">
                                    <td style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;padding-top:14px;padding-bottom:14px;color:#508600">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0"
                                               class="m_5500548108047303379container m_5500548108047303379w520"
                                               width="520"
                                               style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#508600;width:520px!important">
                                            <tbody>
                                            <tr style="box-sizing:border-box;border-collapse:collapse">
                                                <td valign="top"
                                                    style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;font-size:14px;color:#508600">
                                                    <h4 class="m_5500548108047303379ac"
                                                        style="box-sizing:border-box;font-family:inherit;color:inherit;margin-top:10px;margin-bottom:10px;font-size:16px;line-height:20px;font-weight:bold;text-align:center;margin:0">
                                                        Обслужването е завършено?
                                                        <a href="{{env('APP_URL').'/#/'.$vehicle->company_id.'/'.$type.'_reminders/new'}}"
                                                           style="box-sizing:border-box;color:#337ab7;text-decoration:underline"
                                                           target="_blank"
                                                        >Въведете го във Fleet360 →</a>
                                                    </h4>

                                                </td>


                                            </tr>
                                            </tbody>
                                        </table>

                                    </td>

                                </tr>
                                </tbody>
                            </table>
                            {{--<table align="center" border="0" cellpadding="0" cellspacing="0"
                                   class="m_5500548108047303379container m_5500548108047303379w520" width="520"
                                   style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px;width:520px!important">
                                <tbody>
                                <tr style="box-sizing:border-box;border-collapse:collapse">
                                    <td valign="top"
                                        style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                        <h5 style="box-sizing:border-box;font-family:inherit;color:inherit;margin-top:10px;margin-bottom:10px;font-size:14px;line-height:20px;font-weight:bold">
                                            Last Occurred</h5>
                                        <p class="m_5500548108047303379text-warning"
                                           style="box-sizing:border-box;margin:0 0 10px;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#ec8526;font-size:14px">
                                            <strong style="box-sizing:border-box">Never logged in Fleet360</strong></p>

                                    </td>


                                </tr>
                                </tbody>
                            </table>--}}
                            <img alt="----" height="70"
                                 src="https://ci6.googleusercontent.com/proxy/yFC0YBTXTsFRbiAxrD0Qa6x5fTsCtI96FliTSjRa_1tSx0AtulX2Syx5HG40-rn6lgMmgYDqRsxu7WUoyFrS-02Ft5rH8SfYn0YsDqHgvPGIovcFYdW5supDAIooGPDa7lw5TZqM1vW0HPmqb4M4AoQ8HYw=s0-d-e1-ft#http://d3hg8hj412aefj.cloudfront.net/assets/email/divider-e3ff5d50220d8bd93d051180cbdc0e3c.jpg"
                                 width="600"
                                 style="box-sizing:border-box;vertical-align:middle;outline:none;text-decoration:none;display:block;height:auto;line-height:100%"
                                 class="CToWUd">
                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                   class="m_5500548108047303379container m_5500548108047303379w520" width="520"
                                   style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px;width:520px!important">
                                <tbody>
                                <tr style="box-sizing:border-box;border-collapse:collapse">
                                    <td valign="top"
                                        style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                        <p class="m_5500548108047303379muted"
                                           style="box-sizing:border-box;margin:0 0 10px;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;font-size:14px">
                                            Ако се нуждаете от помощ, моля свържете се с нас на <a href="mailto:help@Fleet360.com"
                                                                                      style="box-sizing:border-box;color:#337ab7;text-decoration:underline"
                                                                                      target="_blank">help@Fleet360
                                                .com</a>.</p>
                                        <p class="m_5500548108047303379muted"
                                           style="box-sizing:border-box;margin:0 0 10px;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;font-size:14px">
                                            С най-добри пожелания,
                                            <br style="box-sizing:border-box">
                                            кипът на Fleet360
                                        </p>

                                    </td>


                                </tr>
                                </tbody>
                            </table>

                            <br style="box-sizing:border-box">

                        </td>

                    </tr>
                    </tbody>
                </table>

                <br style="box-sizing:border-box">
                <br style="box-sizing:border-box">
                <table align="center" border="0" cellpadding="0" cellspacing="0"
                       class="m_5500548108047303379footer m_5500548108047303379container m_5500548108047303379w580"
                       width="580"
                       style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;color:#454545;border-collapse:collapse;width:580px!important">
                    <tbody>
                    <tr style="box-sizing:border-box;border-collapse:collapse">
                        <td class="m_5500548108047303379w400" valign="top" width="400"
                            style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;border-collapse:collapse;color:#333">
                            <p style="box-sizing:border-box;margin:0 0 10px;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;color:#333">
                                <b style="box-sizing:border-box">Fleet360.</b>
                                <br style="box-sizing:border-box">
                                <a href="http://email.Fleet360.com/wf/click?upn=XKRoWQTwjVs0DaWlGcFqYu6kXwVTV-2FqbC09BwQIlwSU-3D_lAbSlWHpxQD5GRgOEI-2FJtfSGvNZdje9isdbU55WFrrt8gHzIA0vOuN0JmjqnnVman-2FjpE6IW3lR1GxxEm5Tp-2FahbZYpmsSTD4do4GDlwlgUpLL99Gvdrxeie2rdfKKKL-2FQiXpXCRKF88FHm19HxsIJA8fTLlExlecPOF0wfel7-2BrHzBI54QmGCAetw5WnbKnjvMXcaMohXYiEgLHwd-2Fi5FrjmuuRex8G8Jk7xVVq4IFeG0oYSe5njRmztI0WYKOP"
                                   style="box-sizing:border-box;color:#333;text-decoration:underline" target="_blank"
                                   >fleet360.io</a>
                                | <a href="mailto:help@Fleet360.com"
                                     style="box-sizing:border-box;color:#333;text-decoration:underline" target="_blank">help@Fleet
                                    360.io</a>
                            </p>
                            <p style="box-sizing:border-box;margin:0 0 10px;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;color:#333">
                                <a href="http://email.Fleet360.com/wf/click?upn=XKRoWQTwjVs0DaWlGcFqYu6kXwVTV-2FqbC09BwQIlwSU-3D_lAbSlWHpxQD5GRgOEI-2FJtfSGvNZdje9isdbU55WFrrt8gHzIA0vOuN0JmjqnnVmalrOzux1h4qf5Idu95A21O-2BwRy57UGN8N34gavjKM-2FpVj34WmZsNRESuApeh-2BJntok-2BFIIAgPEFLxK7Yk97utyfnxSjmA-2BQCSo7oOi6sQ-2FnaZ4C5ytYAl17wvCS6JJWr3Es7iCdtB9JR0nC0SVf0VNzJ3YYsEYJjJwORSjKK-2F0-2B-2Bx6Qfwn7h8UXlocaqo-2FMJ0"
                                   title="Fleet360 website"
                                   style="box-sizing:border-box;color:#333;text-decoration:underline" target="_blank"
                                   ><img
                                            alt="Fleet360"
                                            src="https://ci4.googleusercontent.com/proxy/d95qV9N0JTbBQtOV2XE1yUBEaVkF-RlMiDGKTdYZGIWWHrMLCXbayvXdwFRHo46L5krtru-BPU8gb41JKLgYpu0OgsI-LzHBPbAkUZpqpUEs83dTkkPeWlV5kPVLdi0OQBmf4q28xHQM_tOvOILxOZ00u5ReXXYaUg=s0-d-e1-ft#http://d3hg8hj412aefj.cloudfront.net/assets/email/Fleet360-logo-6fb12f2ffd8e558f09d3ae903687c73d.gif"
                                            style="box-sizing:border-box;vertical-align:middle;outline:none;text-decoration:none;display:block;height:auto;line-height:100%;border:none"
                                            class="CToWUd">
                                </a></p>

                        </td>
                        {{--<td class="m_5500548108047303379w180" valign="top" width="180"
                            style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;border-collapse:collapse;color:#333">
                            <p class="m_5500548108047303379address"
                               style="box-sizing:border-box;margin:0 0 10px;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:16px;color:#333">
                                1500 First Avenue North
                                <br style="box-sizing:border-box">
                                Unit #77
                                <br style="box-sizing:border-box">
                                Birmingham, AL 35203
                                <br style="box-sizing:border-box">
                                1.800.975.5304
                            </p>

                        </td>--}}


                    </tr>
                    </tbody>
                </table>
                <br style="box-sizing:border-box">
                <br style="box-sizing:border-box">


            </td>

        </tr>
        </tbody>
    </table>


    <img src="https://ci4.googleusercontent.com/proxy/7NLhpQ1CpjycOax76kyJ7orrH64THUHWagtyjhvW9aejhGhsMOfI0Z1w3YtpB1eGjR0Tb4znIiHEx8NB88kM0RxtC5b4ni-KYJb3VHgm8Fsj-6q5VDSJzj9PI2xJod1072f2lTsvYITT_xRV0fbDlgKnXxNVpgJ8x8CGPHBLiXqAboHOd0d7W2M-PdcmLuquUihfO-KcRF9QHrjqRPTwOj8eemY1udIMKEEW0f5oC3TtFs38kZYDU7KOcGxUHw7jnhIuCAN_dw5A_E2srmKpqm8O-O2QdhZJFWDKAf1YGj6B2DdXSNRzzlvOFj2UuyptpLt-PFf1FuwZFIXjTtQ-lxV_VE9meqfO-AkbRv7d6bN955BgD9N1O0VkBe5YpAIzaglCKvqqe6p-PMmxefx2G89rmZbsniQ2uyQNNZJX1kB8zw=s0-d-e1-ft#http://email.Fleet360.com/wf/open?upn=lAbSlWHpxQD5GRgOEI-2FJtfSGvNZdje9isdbU55WFrrt8gHzIA0vOuN0JmjqnnVmamCCQ5ueqrSVYHGl6nFmXttD4X2YvS9Cgtu3EIAZfOKznrbQsDKLW2AsHUm5tr7riOMVZxwNyJR9D8Fco1TT-2FpwyS6VTaAaF7SPFGSwNpnfSOj7J3mmdQtJxgK2duFFpf3XVUtfribcuu2wee4oOfpU7uGwpOdAqcsZMA8P4lPzk4P4viEu6ONZ-2FzX7oJ9yHW"
         alt="" width="1" height="1" border="0"
         style="height: 1px !important; width: 1px !important; border-width: 0px !important; margin: 0px !important; padding: 0px !important; display: none !important;"
         class="CToWUd" hidden="">
    <div class="yj6qo"></div>
    <div class="adL">
    </div>
</div>
</body>
</html>