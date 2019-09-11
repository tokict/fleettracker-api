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
    <table bgcolor="#dddddd" border="0" cellpadding="0" cellspacing="0" class="m_-5065011133745942605wrapper"
           width="100%"
           style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;color:#454545;border-collapse:collapse;margin:0;padding:0;table-layout:fixed;width:100%;line-height:100%">
        <tbody>
        <tr style="box-sizing:border-box;border-collapse:collapse">
            <td style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;color:#454545;border-collapse:collapse">
                <br style="box-sizing:border-box">
                <table align="center" bgcolor="#ffffff" border="1" cellpadding="0" cellspacing="0"
                       class="m_-5065011133745942605section m_-5065011133745942605w600" frame="border" rules="none"
                       width="600"
                       style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;color:#454545;border:1px solid #c4c4c4;border-spacing:0px;border-collapse:collapse;background-color:#fff;width:600px!important">
                    <tbody>
                    <tr style="box-sizing:border-box;border-collapse:collapse">
                        <td style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                   class="m_-5065011133745942605section-alert m_-5065011133745942605w600"
                                   style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;border-collapse:collapse;background-color:#fefde2;border-width:1px 0 1px 0;border-style:solid;border-color:#e5e181;color:#666;margin-bottom:20px;font-size:14px;width:600px!important;border-top-width:0"
                                   width="600">
                                <tbody>
                                <tr style="box-sizing:border-box;border-collapse:collapse">
                                    <td style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px;padding-top:14px;padding-bottom:14px">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0"
                                               class="m_-5065011133745942605container m_-5065011133745942605w520"
                                               width="520"
                                               style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px;width:520px!important">
                                            <tbody>
                                            <tr style="box-sizing:border-box;border-collapse:collapse">
                                                <td valign="top"
                                                    style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                                    <h2 style="box-sizing:border-box;font-family:inherit;font-weight:500;color:inherit;margin-top:20px;margin-bottom:10px;font-size:24px;line-height:36px">

                                                        @if($type == 'renewal')
                                                            Напомняне за подновяване
                                                        @else
                                                            Напомняне за обслужване
                                                        @endif


                                                    </h2>
                                                </td>


                                            </tr>
                                            </tbody>
                                        </table>
                                        <table align="center" border="0" cellpadding="5" cellspacing="0"
                                               class="m_-5065011133745942605container m_-5065011133745942605w520"
                                               width="520"
                                               style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px;width:520px!important">
                                            <tbody>
                                            <tr style="box-sizing:border-box;border-collapse:collapse">
                                                <td class="m_-5065011133745942605w40" valign="top" width="40"
                                                    style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                                    <img alt="{{$vehicle->name}}" height="40"
                                                         src="{{env('APP_URL').'/'.($vehicle->pic?$vehicle->pic:'img/car.png')}}"
                                                         width="40"
                                                         style="box-sizing:border-box;vertical-align:middle;outline:none;text-decoration:none;display:block;height:auto;line-height:100%"
                                                         class="CToWUd">

                                                </td>
                                                <td valign="top"
                                                    style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                                    <table style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                                        <tbody>
                                                        <tr style="box-sizing:border-box;border-collapse:collapse">
                                                            <td class="m_-5065011133745942605w130" valign="top"
                                                                width="130"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                                                <span class="m_-5065011133745942605muted"
                                                                      style="box-sizing:border-box">Превозно средство:</span>

                                                            </td>
                                                            <td valign="top"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
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
                                                            <td class="m_-5065011133745942605w130" valign="top"
                                                                width="130"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                                                <span class="m_-5065011133745942605muted"
                                                                      style="box-sizing:border-box">Вид на подновяване
                                                                    :</span>

                                                            </td>
                                                            <td valign="top"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                                                <strong style="box-sizing:border-box">{{$task}}</strong>

                                                            </td>
                                                        </tr>
                                                        <tr style="box-sizing:border-box;border-collapse:collapse">
                                                            <td class="m_-5065011133745942605w130" valign="top"
                                                                width="130"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                                                <span class="m_-5065011133745942605muted"
                                                                      style="box-sizing:border-box">Група:</span>

                                                            </td>
                                                            <td valign="top"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                                                <strong style="box-sizing:border-box">{{isset($group->name)?$group->name:''}}</strong>

                                                            </td>
                                                        </tr>
                                                        <tr style="box-sizing:border-box;border-collapse:collapse">
                                                            <td class="m_-5065011133745942605w130" valign="top"
                                                                width="130"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                                                <span class="m_-5065011133745942605muted"
                                                                      style="box-sizing:border-box">{{ucfirst(__('labels.driver_or_operator'))}}:</span>

                                                            </td>
                                                            <td valign="top"
                                                                style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
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
                                   class="m_-5065011133745942605container m_-5065011133745942605w520" width="520"
                                   style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px;width:520px!important">
                                <tbody>
                                <tr style="box-sizing:border-box;border-collapse:collapse">
                                    <td valign="top"
                                        style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;border-collapse:collapse;font-size:14px">
                                        <p style="box-sizing:border-box;margin:0 0 10px;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;font-size:14px">
                                            Изтича на: <strong style="box-sizing:border-box">{{$trigger}}</strong>
                                            <small class="m_-5065011133745942605muted"
                                                   style="box-sizing:border-box;font-size:85%">(<span class="aBn"
                                                                                                      data-term="goog_1227170063"
                                                                                                      tabindex="0"><span
                                                            class="aQJ">след {{$difference}} дни</span></span>)
                                            </small>
                                        </p>
                                        <ul style="box-sizing:border-box;margin-top:0;margin-bottom:10px;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;line-height:20px;color:#454545;font-size:14px">
                                            <li style="box-sizing:border-box"><a
                                                        href="{{env('APP_URL').'/#/'.$vehicle->company_id.'/'.$type.'_reminders/'.$reminder->id.'/edit'}}"
                                                        style="box-sizing:border-box;color:#337ab7;text-decoration:underline"
                                                        target="_blank"
                                                        >Редактирай това напомняне</a></li>
                                            <li style="box-sizing:border-box"><a
                                                        href="{{env('APP_URL').'/#/'.$vehicle->company_id.'/vehicles/'.$vehicle->id.'/reminders'}}"
                                                        style="box-sizing:border-box;color:#337ab7;text-decoration:underline"
                                                        target="_blank"
                                                        >Управлявай всички напомняния за подновяване за {{$vehicle->name}}</a></li>
                                        </ul>

                                    </td>


                                </tr>
                                </tbody>
                            </table>
                            <img alt="----" height="70"
                                 src="https://ci6.googleusercontent.com/proxy/yFC0YBTXTsFRbiAxrD0Qa6x5fTsCtI96FliTSjRa_1tSx0AtulX2Syx5HG40-rn6lgMmgYDqRsxu7WUoyFrS-02Ft5rH8SfYn0YsDqHgvPGIovcFYdW5supDAIooGPDa7lw5TZqM1vW0HPmqb4M4AoQ8HYw=s0-d-e1-ft#http://d3hg8hj412aefj.cloudfront.net/assets/email/divider-e3ff5d50220d8bd93d051180cbdc0e3c.jpg"
                                 width="600"
                                 style="box-sizing:border-box;vertical-align:middle;outline:none;text-decoration:none;display:block;height:auto;line-height:100%"
                                 class="CToWUd">
                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                   class="m_-5065011133745942605container m_-5065011133745942605w520" width="520"
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
                       class="m_-5065011133745942605footer m_-5065011133745942605container m_-5065011133745942605w580"
                       width="580"
                       style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;color:#454545;border-collapse:collapse;width:580px!important">
                    <tbody>
                    <tr style="box-sizing:border-box;border-collapse:collapse">
                        <td class="m_-5065011133745942605w400" valign="top" width="400"
                            style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;border-collapse:collapse;color:#333">
                            <p style="box-sizing:border-box;margin:0 0 10px;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;color:#333">
                                <b style="box-sizing:border-box">Fleet360.</b>
                                <br style="box-sizing:border-box">
                                <a href="http://email.fleet360.io/wf/click?upn=XKRoWQTwjVs0DaWlGcFqYu6kXwVTV-2FqbC09BwQIlwSU-3D_lAbSlWHpxQD5GRgOEI-2FJtfSGvNZdje9isdbU55WFrrvWxUxb0ICik6HQ-2FRBXkIpSJMNOWu8EiitSlRqk4QYS6zClevmBb-2B28hcBt0j7F45a3MDxAA92x2elef-2B0QQ9fRyMI9Nj7e0rGLeQj-2B0GCU5JZefC801vlviDknfcgci0UeJVf82plqeW0pqNUWXKZO9GuyX-2B2lQB356HpsA7uBiVmVNiVBmFKSTZv1ux4sSAmcVOetJtbmiBAFslNAFgLt"
                                   style="box-sizing:border-box;color:#333;text-decoration:underline" target="_blank"
                                   data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://email.fleet360.io/wf/click?upn%3DXKRoWQTwjVs0DaWlGcFqYu6kXwVTV-2FqbC09BwQIlwSU-3D_lAbSlWHpxQD5GRgOEI-2FJtfSGvNZdje9isdbU55WFrrvWxUxb0ICik6HQ-2FRBXkIpSJMNOWu8EiitSlRqk4QYS6zClevmBb-2B28hcBt0j7F45a3MDxAA92x2elef-2B0QQ9fRyMI9Nj7e0rGLeQj-2B0GCU5JZefC801vlviDknfcgci0UeJVf82plqeW0pqNUWXKZO9GuyX-2B2lQB356HpsA7uBiVmVNiVBmFKSTZv1ux4sSAmcVOetJtbmiBAFslNAFgLt&amp;source=gmail&amp;ust=1499859946255000&amp;usg=AFQjCNE9YLjKa9iZ8m3s6E-t-sHnx5Hl4g">fleet360.io</a>
                                | <a href="mailto:help@fleet360.io"
                                     style="box-sizing:border-box;color:#333;text-decoration:underline" target="_blank">help@fleet360.io</a>
                            </p>
                            <p style="box-sizing:border-box;margin:0 0 10px;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;color:#333">
                                <a href="http://email.fleet360.io/wf/click?upn=XKRoWQTwjVs0DaWlGcFqYu6kXwVTV-2FqbC09BwQIlwSU-3D_lAbSlWHpxQD5GRgOEI-2FJtfSGvNZdje9isdbU55WFrrvWxUxb0ICik6HQ-2FRBXkIpS8SugqyU5cMRUs-2Fuv3xj348YIUV1WiRctNwt8bkaFE-2FKeo1iJ2o502uQsJzdngHJfEaAS4XgA9iSU6CrjQmgBauZpjwHOlB21g3pLK6w1CbJpEEk0kbWF1vPpM8oMmJFV0pd7uUg7yDNqR-2B9pRSckrt40pRYz2ob8mfoD-2BjHm6N41accJ2NHAWZ8CB5eG3l8U"
                                   title="Fleetio website"
                                   style="box-sizing:border-box;color:#333;text-decoration:underline" target="_blank"
                                   data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://email.fleet360.io/wf/click?upn%3DXKRoWQTwjVs0DaWlGcFqYu6kXwVTV-2FqbC09BwQIlwSU-3D_lAbSlWHpxQD5GRgOEI-2FJtfSGvNZdje9isdbU55WFrrvWxUxb0ICik6HQ-2FRBXkIpS8SugqyU5cMRUs-2Fuv3xj348YIUV1WiRctNwt8bkaFE-2FKeo1iJ2o502uQsJzdngHJfEaAS4XgA9iSU6CrjQmgBauZpjwHOlB21g3pLK6w1CbJpEEk0kbWF1vPpM8oMmJFV0pd7uUg7yDNqR-2B9pRSckrt40pRYz2ob8mfoD-2BjHm6N41accJ2NHAWZ8CB5eG3l8U&amp;source=gmail&amp;ust=1499859946255000&amp;usg=AFQjCNEotrIOObda2NEDEzYTtctyv7CjiA"><img
                                            alt="Fleetio"
                                            src="https://ci4.googleusercontent.com/proxy/d95qV9N0JTbBQtOV2XE1yUBEaVkF-RlMiDGKTdYZGIWWHrMLCXbayvXdwFRHo46L5krtru-BPU8gb41JKLgYpu0OgsI-LzHBPbAkUZpqpUEs83dTkkPeWlV5kPVLdi0OQBmf4q28xHQM_tOvOILxOZ00u5ReXXYaUg=s0-d-e1-ft#http://d3hg8hj412aefj.cloudfront.net/assets/email/fleetio-logo-6fb12f2ffd8e558f09d3ae903687c73d.gif"
                                            style="box-sizing:border-box;vertical-align:middle;outline:none;text-decoration:none;display:block;height:auto;line-height:100%;border:none"
                                            class="CToWUd">
                                </a></p>

                        </td>
                       {{-- <td class="m_-5065011133745942605w180" valign="top" width="180"
                            style="box-sizing:border-box;font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;border-collapse:collapse;color:#333">
                            <p class="m_-5065011133745942605address"
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


    <img src="https://ci3.googleusercontent.com/proxy/Cz9UY5-Q5cm3KfBq1IXw4IbYUL-oGO7wrk70W_LA7tjt4uyXLXffDmZU3zUIxq9zCOJdhpVkKpUviYFdEOwfnvrR7wOGe70e--EjzbtLcyGoFV6RrxOxRDHIMycfV4UPL9OUvkOLk1JXnOGdJRSXQkVOCi_fJgsYDWaOMqHCrEJI702JFaFLFnT0PP4G3gQrkTiWvNcwiq31gaNeKODb5JjTTs4EDs0wrfHaQwOZkKg48welLyIli2yduUKkkkaqT6hN_iAFRnuTmOKNwHdOJYXskRZhlyvDXX4MSbxestXIWWZGZn21VOPcrTiXiP-NN8d5mHtzYN82zt3pd7zT5dZuRGDbSccgJwyLIHUWfDP5w_ckzbxHt_WAjN13UARaLQp_E6rQ8gZHjQZXAbkkEM3IQy16qAzakv3n_RMazvGmrOgWqezhsFog=s0-d-e1-ft#http://email.fleet360.io/wf/open?upn=lAbSlWHpxQD5GRgOEI-2FJtfSGvNZdje9isdbU55WFrrvWxUxb0ICik6HQ-2FRBXkIpSMx5I2beny-2F6DGhkFPWRbyx749U-2BDwIo0-2BljtBu3y3r4ZckEZlfkMsG97umjuYS9IlynP4Lmgfwwm6TGLsqiewb9V8TIjfef2-2FkbwIgWJWjyKS61bNaNpv7WycA3iqge4hCig4yqZUV7KVYlzHiWPAT7pXHdLaLpbKXnBvmwQKBXlUpcknTOT1VO2-2FkT3TevL"
         alt="" width="1" height="1" border="0"
         style="height: 1px !important; width: 1px !important; border-width: 0px !important; margin: 0px !important; padding: 0px !important; display: none !important;"
         class="CToWUd" hidden="">
    <div class="yj6qo"></div>
    <div class="adL">
    </div>
</div>
</body>
</html>