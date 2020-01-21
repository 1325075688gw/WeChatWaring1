{literal}
<style>
    .multiselect-col .btn-group{
        width: 100%;
    }
    .multiselect-col .btn-group .btn{
        width: 100% !important;
        border-radius: 4px !important;
    }
    .multiselect-col .btn-group .multiselect-container li input[type="checkbox"]{
        visibility: hidden;
    }
</style>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title pull-left"><i class="fa fa-ticket"></i> 优惠券管理</h3>

        <button class="btn btn-info btn-xs pull-right" ng-click="helpful()">
            <i class="fa fa-info"></i> 说明
        </button>
        <button ng-if="auth.hasAction('/sales/ajax_post.php?sAction=save_coupon_sales')" class="btn btn-warning btn-xs pull-right"
                ng-click="addNew()">
            <i class="fa fa-plus-square"></i> 新增
        </button>
    </div>
    <div class="panel-body">
        <form class="form-inline form-search">
            <input ng-model="searchParam.sName" ng-change="runSearch()"
                   type="text" placeholder="优惠券名称" class="form-control m_l_10">
            <input ng-model="searchParam.sId" ng-change="runSearch()"
                   type="text" placeholder="优惠券ID" class="form-control m_l_10">
            <input ng-model="searchParam.sSn" ng-change="runSearch()"
                   type="text" placeholder="优惠券序列号" class="form-control m_l_10">
            <select  ng-options="o.value as o.label for o in distributor"
                     class="form-control" ng-change="runSearch()" ng-model="searchParam.iDistributor">
                <option value="">全部</option>
            </select>
            <select class="form-control" ng-change="runSearch()" ng-model="searchParam.iSendPurpose">
                <option value="">全部</option>
                <option ng-repeat="(value, label) in send_purpose" value="{{value}}">{{label}}</option>
            </select>
            <select class="form-control" ng-change="runSearch()" ng-model="searchParam.iSendWay">
                <option value="">全部</option>
                <option ng-repeat="(value, label) in send_way" value="{{value}}">{{label}}</option>
            </select>
            <select class="form-control" ng-change="runSearch()" ng-model="searchParam.iUsedScope">
                <option value="">全部</option>
                <option ng-repeat="(value, label) in used_scope" value="{{value}}">{{label}}</option>
            </select>
            <select class="form-control" ng-change="runSearch()" ng-model="searchParam.iAvailable">
                <option value="-1" ng-selected="true">全部</option>
                <option value="1">有效</option>
                <option value="0">无效</option>
            </select>
            <button  class="btn btn-warning btn-xs" ng-click="getLinkById()">
                <i class="fa fa-link"></i> 生成领取链接
            </button>
        </form>

    </div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th width="5%">ID</th>
            <th width="5%">部门</th>
            <th width="10%">优惠券名称</th>
            <th width="5%">使用类型</th>
            <th width="7%">发送方式</th>
            <th width="7%">限制频道</th>
            <th width="7%">限制品类</th>
            <th width="15%">到期时间</th>
            <th width="5%">面值</th>
            <th width="7%">满减金额</th>
            <th width="5%">折扣</th>
            <th width="7%">最高抵扣</th>
            <th width="5%">数量</th>
            <th width="5%">余量</th>
            <th width="6%">添加人</th>
            <th width="13%">查看</th>
            <th width="10%">操作</th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="one in list">
            <td>{{one.id}}</td>
            <td>{{department[one.department]}}</td>
            <td>{{one.name}}</td>
            <td>{{usedTypes[one.used_type]}}</td>
            <td>{{send_way[one.send_way]}}</td>
            <td>
                    <span ng-repeat="scope in one.new_used_scope">
                        {{used_scope[scope] ? used_scope[scope] : '全站通用(特定产品除外)' }}
                    </span>
            </td>
            <td>{{one.used_condition_sales_type_name||'无'}}</td>
            <td>{{one.expiry_type != 2 ? one.used_end_time : one.expiry_days+'天后'}}</td>
            <td>
                <span ng-if="one.type==0">
                    {{one.price}}
                </span>
            </td>
            <td>{{one.used_condition_price== 0 ?'无' :one.used_condition_price}}</td>
            <td>
                <span ng-if="one.type==1">
                    {{one.rate}}折
                </span>
            </td>
            <td>
                <span ng-if="one.type==1">
                    {{one.rate_max_price== 0 ?'无' :one.rate_max_price}}
                </span>
            </td>
            <td>{{one.num}}</td>
            <td>{{one.remain_num}}</td>
            <td>{{one.admin_name}}</td>
            <td ng-if="one.send_way==2">
                <a class="btn btn-warning btn-xs" ng-click="view(one)" href="javascript:;">查看</a>
                <a class="btn btn-info btn-xs" ng-click="generate(one)" href="javascript:;">生成</a>
                <a class="btn btn-danger btn-xs" ng-click="statistics(one)" href="javascript:;">统计</a>
            </td>
            <td ng-if="one.send_way==1">
                <a class="btn btn-danger btn-xs" ng-click="statistics(one)" href="javascript:;">统计</a>
            </td>
            <td>
                <a class="btn btn-info btn-xs" ng-click="modify(one)" href="javascript:;">
                    <i ng-class="{'fa-pencil':!one.loading, 'fa-spinner fa-spin':one.loading}" class="fa"></i> 修改
                </a>
                <a class="btn btn-danger btn-xs" href="javascript:;" ng-click="remove(list, one)" ng-show="one.batch_no != exchange_code_channel">
                    <i class="fa fa-trash-o"></i>
                    <span ng-if="one.num == one.remain_num">删除</span>
                    <span ng-if="one.num != one.remain_num">作废</span>
                </a>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<uib-pagination ng-show="total>0" total-items="total" ng-model="pageNo" max-size="10" items-per-page="pageSize" ng-change="pageChanged()" class="pagination-sm" boundary-link-numbers="true" rotate="false" next-text="下一页" previous-text="上一页"></uib-pagination>

<div id="edit" class="modal" ng-if="showModaledit==1" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h4 class="pull-left modal-title">优惠券<small>（ 红框内为必填项 ）</small><span class="nowrap" ng-if="detail.sn">SN: {{detail.sn}}</span></h4>
                <button class="btn btn-warning btn-sm pull-right"
                        ng-click="closeModal('edit')"><i class="fa fa-times"></i> 关闭</button>
                <button class="btn btn-success btn-sm pull-right m_r_10" ng-if="auth.hasAction('/sales/ajax_post.php?sAction=save_coupon_sales') && savingCoupon">
                    <i class="fa fa-spinner fa-spin"></i> 保存中</button>
                <button class="btn btn-success btn-sm pull-right m_r_10" ng-if="auth.hasAction('/sales/ajax_post.php?sAction=save_coupon_sales') && !savingCoupon" ng-click="save()">
                    <i class="fa fa-save"></i> 保存</button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form class="form-horizontal" name="forms.coupon_form">
                        {* ================================================================================== *}
                        <div class="row">
                            <div class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">资金池名称</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select class="form-control" ng-model="detail.cash_pool_name" required>
                                        <option ng-repeat="(value, label) in department"  value="{{value}}" >{{label}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-4 sm-padding-col">
                                <label class="col-md-3 control-label sm-padding-col">预估核销率</label>
                                <div class="col-md-9 sm-padding-col">
                                    <input ng-model="detail.expected_rate" type="text" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        {* ================================================================================== *}
                        <div class="row">
                            <div class="form-group col-md-4 sm-padding-col" >
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">名称</label>
                                <div class="col-md-9 sm-padding-col">
                                    <input ng-model="detail.name" type="text" class="form-control" id="ipt_1" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">发放部门</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select class="form-control" ng-model="detail.department" required ng-disabled="detail.id">
                                        <option ng-repeat="(value, label) in department"  value="{{value}}"  ng-if="value != 10 && value != 11 && value != 14 ">{{label}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col" ng-show="detail.department==1">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">二级部门</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select class="form-control" ng-model="detail.sub_department"  ng-required="detail.department==1" ng-disabled="detail.id">
                                        <option ng-repeat="(value, label) in sub_department"  value="{{value}}">{{label}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">发放用途</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select class="form-control" ng-model="detail.send_purpose" required>
                                        <option ng-repeat="(value, label) in send_purpose" value="{{value}}">{{label}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">发放方式</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select class="form-control" ng-model="detail.send_way" required ng-disabled="detail.id">
                                        <option ng-repeat="(value, label) in send_way" value="{{value}}">{{label}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">消费类型</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select class="form-control" ng-model="detail.consume_type" required>
                                        <option ng-repeat="(value, name) in consume_type" value="{{value}}">{{name}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-8 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">发放渠道</label>
                                <div class="col-md-9 sm-padding-col form-item ng-scope">
                                    <select
                                            ng-model="detail.channel_ids"
                                            class="form-control"
                                            placeholder="请填写发放渠道"
                                            mfw-select
                                            multiple
                                            optionlabel="detail.channellabel"
                                            url="/ajax.php?sModule=common&sController=options&sAction=couponchannelSuggest"
                                            ng-disabled="detail.id && detail.batch_no == exchange_code_channel"
                                    ></select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">使用类型</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select class="form-control" ng-model="detail.used_type" required>
                                        <option ng-repeat="(value, label) in usedTypes" value="{{value}}" >
                                            {{label}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">优惠类型</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select class="form-control" ng-model="detail.type" required ng-disabled="detail.id">
                                        <option ng-repeat="(value, label) in coupon_type" value="{{value}}" ng-if="value!=2 || allowVariableCoupon()">{{label}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col">
                                <label class="col-md-3 control-label sm-padding-col">数量</label>
                                <div class="col-md-9 sm-padding-col">
                                    <input ng-model="detail.num" type="text" class="form-control" required ng-disabled="detail.id && detail.batch_no == exchange_code_channel">
                                </div>
                            </div>

                            <div class="form-group col-md-4 sm-padding-col" ng-if="detail.used_scope.length == 1 && detail.used_scope[0] == 2 && detail.used_scope_type==2">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">商家补贴</label>
                                <div class="col-md-9 sm-padding-col form-item ng-scope">
                                    <select class="form-control" ng-model="detail.ota_proportion" required ng-disabled="detail.id">
                                        <option ng-repeat="(value, label) in ota_proportion" value="{{value}}">{{label}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">满</label>
                                <div class="col-md-9 sm-padding-col">
                                    <div class="input-group">
                                        <input ng-model="detail.used_condition_price" type="text" class="form-control" id="ipt_1" required ng-disabled="detail.id">
                                        <span class="input-group-addon">元</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col" ng-if="detail.type==1">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">折扣</label>
                                <div class="col-md-9 sm-padding-col">
                                    <div class="input-group">
                                        <input ng-model="detail.rate" type="text"
                                               placeholder="填写数值需 ≥ 5，如8(代表8折)"
                                               class="form-control"
                                               required
                                               ng-pattern="/^[1-9](\.\d)?$/"
                                               ng-disabled="detail.id">
                                        <span class="input-group-addon">折</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col" ng-if="detail.type==1">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">最高抵扣</label>
                                <div class="col-md-9 sm-padding-col">
                                    <div class="input-group">
                                        <input ng-model="detail.rate_max_price" type="text" placeholder="最高抵扣金额,如: 500" class="form-control" required ng-disabled="detail.id">
                                        <span class="input-group-addon">元</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col" ng-if="detail.type==0">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">减价</label>
                                <div class="col-md-9 sm-padding-col">
                                    <div class="input-group">
                                        <input ng-model="detail.price" type="text" class="form-control" id="ipt_1" placeholder="如：5，50，100，300" required ng-disabled="detail.id">
                                        <span class="input-group-addon">元</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col" ng-if="detail.type==2">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">金额下限</label>
                                <div class="col-md-9 sm-padding-col">
                                    <div class="input-group">
                                        <input ng-model="detail.variable_price_min" type="text" class="form-control" required ng-disabled="detail.id">
                                        <span class="input-group-addon">元</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col" ng-if="detail.type==2">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">金额上限</label>
                                <div class="col-md-9 sm-padding-col">
                                    <div class="input-group">
                                        <input ng-model="detail.variable_price_max" type="text" class="form-control" required ng-disabled="detail.id">
                                        <span class="input-group-addon">元</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">领取时间</label>
                                <div class="col-md-9 sm-padding-col">
                                    <input ng-model="detail.send_start_time" ng-change="updateRelativeUsedStartTime()" mfw-date-time-picker="YYYY-MM-DD HH:mm:ss" type="text" class="form-control" required ng-disabled="detail.id && detail.batch_no == exchange_code_channel">
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">到</label>
                                <div class="col-md-9 sm-padding-col">
                                    <input ng-model="detail.send_end_time" mfw-date-time-picker="YYYY-MM-DD HH:mm:ss" type="text" class="form-control" required
                                           ng-disabled="detail.id && detail.batch_no == exchange_code_channel"
                                           ng-change="fixEndTime(detail,'send_end_time')"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4  sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">使用时间</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select class="form-control" ng-model="detail.expiry_type" ng-change="updateRelativeUsedStartTime()" required ng-disabled="detail.id">
                                        <option ng-repeat="(value, label) in expiry_type" ng-if="!detail.send_way || detail.send_way==1 || detail.send_way==2 && value==1" value="{{value}}">{{label}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-4 sm-padding-col" ng-if="detail.expiry_type == 2">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">生效时间</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select  ng-model="detail.active_offset_days" class="form-control" required  ng-disabled="detail.id"
                                             ng-options="d as (d>0?('领取后第'+d+'天'):'领取时') for d in ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15']"></select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col" ng-if="detail.expiry_type == 2">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">至生效后</label>
                                <div class="col-md-9 sm-padding-col">
                                    <div class="input-group">
                                        <input ng-model="detail.expiry_days" type="text" class="form-control" required ng-disabled="detail.id">
                                        <span class="input-group-addon">天</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col"  ng-if="detail.expiry_type == 1">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col" style="overflow: hidden">可用时间</label>
                                <div class="col-md-9 sm-padding-col">
                                    <input ng-model="detail.used_start_time" mfw-date-time-picker="YYYY-MM-DD HH:mm:ss" type="text" class="form-control" required ng-disabled="detail.expiry_type == 2 || (detail.id && detail.batch_no == exchange_code_channel)">
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col" ng-if="detail.expiry_type == 1">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">有效期至</label>
                                <div class="col-md-9 sm-padding-col">
                                    <input ng-model="detail.used_end_time" type="text" class="form-control"
                                           mfw-date-time-picker="YYYY-MM-DD HH:mm:ss"  required ng-disabled="detail.id"
                                           ng-change="fixEndTime(detail,'used_end_time')">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <!--
                            <div class="form-group col-md-4 sm-padding-col" >
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">频道限制</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select ng-model="detail.used_scope_type" ng-change="changeUsedScopeType()"
                                            required ng-disabled="detail.id" class="form-control"
                                            ng-options="item.v as item.l for item in [{v:1,l:'全站通用(特定产品除外)'},{v:2,l:'按频道选择'}]">
                                    </select>
                                </div>
                            </div>
                            -->
                            <div ng-if="detail.used_scope_type==2" class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">频道选择</label>
                                <div class="col-md-9 sm-padding-col multiselect-col">
                                    <select  multiselect class="form-control" ng-change="getFlightType()"
                                             required={{detail.used_scope_type==2}}
                                             multiple="multiple" ng-model="detail.used_scope" ng-disabled="detail.id">
                                        <option ng-repeat="(value, label) in used_scope" value="{{value}}">{{label}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col" >
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">身份限制</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select ng-model="detail.used_condition_tag"
                                            class="form-control"
                                            ng-options="item.tag as item.name for item in used_condition_tag">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">限客户端</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select class="form-control"  ng-model="detail.used_condition_client">
                                        <option ng-repeat="(value, name) in used_client" value="{{value}}">{{name}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-4 sm-padding-col" ng-if="detail.ota_proportion == 0 && isSalesScope() && detail.used_scope_type==2">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">商家专用</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select class="form-control" ng-model="detail.assign_ota" ng-disabled="detail.id">
                                        <option value="0" ng-selected="!detail.id || detail.assign_ota == 0">否</option>
                                        <option value="1" ng-selected="detail.assign_ota == 1">是</option>
                                    </select>
                                </div>
                            </div>

                            <div ng-if="detail.assign_ota == 1 || detail.ota_id>0" class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">商家ID</label>
                                <div class="col-md-9 sm-padding-col">
                                    <input ng-model="detail.ota_id" ng-pattern="/^[1-9](\d+)$/" placeholder="只能指定一个商家" type="text" required ng-disabled="detail.id" class="form-control">
                                </div>
                            </div>

                            <div ng-if="detail.assign_ota == 1 || detail.ota_id>0" class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">展示位置</label>
                                <div class="col-md-9 sm-padding-col multiselect-col">
                                    <select multiselect class="form-control " multiple="multiple" ng-model="detail.used_condition_position">
                                        <option ng-repeat="(value, name) in show_position" value="{{value}}">{{name}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-4 sm-padding-col" ng-if=" detail.ota_proportion == 0 && showSalesColumn()">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">限制维度</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select ng-options="o.value as o.label for o in sales_column|filter:{value:true}:allowSalesColumn"
                                            class="form-control" ng-model="detail.used_sales_column"
                                            ng-disabled="detail.id" ng-change="handSalesColumn()">
                                    </select>
                                </div>
                            </div>

                            <div ng-if="detail.ota_proportion == 0 && detail.used_sales_column==0 && allowSalesColumn(0)" class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">品类限制</label>
                                <div class="col-md-9 sm-padding-col multiselect-col">
                                    <select  multiselect class="form-control " multiple="multiple" ng-model="detail.used_condition_sales_type">
                                        <option ng-repeat="(value, name) in sales_type" value="{{value}}">{{name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div ng-if="detail.ota_proportion == 0 && detail.used_sales_column == 1 && allowSalesColumn(1)" class="form-group col-md-8">
                                <div class="input-group col-md-12 m_b_10">
                                    <input type="text" class="form-control" ng-model="insert.sId" placeholder="输入限制优惠的产品ID">
                                    <span class="input-group-btn">
                                            <button class="btn btn-default" ng-click="attachSales()">增加<span ng-if="isAddLoading">中...</span></button>
                                        </span>

                                    <span class="input-group-btn">
                                            <button class="btn btn-default" ng-click="removeAttachList()">清空<span ng-if="isDelLoading">中...</span></button>
                                        </span>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th width="20%">产品ID</th>
                                        <th width="70%">产品名称</th>
                                        <th width="10%">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="row in attachedSales">
                                        <td ng-bind="row.id"></td>
                                        <td ng-bind="row.name"></td>
                                        <td><button class="btn btn-xs btn-default" ng-click="removeAttach(row)">删除</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div ng-if="detail.ota_proportion == 0 && detail.used_sales_column == 2 && allowSalesColumn(2)" class="form-group col-md-8">
                                <div class="input-group col-md-12 m_b_10">
                                    <input type="text" class="form-control" ng-model="insert.sId" placeholder="输入限制优惠专题的ID">
                                    <span class="input-group-btn">
                                            <button class="btn btn-default" ng-click="attachSales()">增加<span ng-if="isAddLoading">中...</span></button>
                                        </span>
                                    <span class="input-group-btn">
                                            <button class="btn btn-default" ng-click="removeAttachList()">清空<span ng-if="isDelLoading">中...</span></button>
                                        </span>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th width="20%">专题ID</th>
                                        <th width="70%">名称</th>
                                        <th width="10%">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="row in attachedSales">
                                        <td ng-bind="row.id"></td>
                                        <td ng-bind="row.name"></td>
                                        <td><button class="btn btn-xs btn-default" ng-click="removeAttach(row)">删除</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>


                            <div ng-if="detail.ota_proportion == 0 && detail.used_sales_column == 3 && allowSalesColumn(3)" class="form-group col-md-8">
                                <div class="input-group col-md-12 m_b_10">
                                    <input type="text" class="form-control" ng-model="insert.sId" placeholder="输入限制优惠活动的ID">
                                    <span class="input-group-btn">
                                            <button class="btn btn-default" ng-click="attachSales()">增加<span ng-if="isAddLoading">中...</span></button>
                                        </span>

                                    <span class="input-group-btn">
                                            <button class="btn btn-default" ng-click="removeAttachList()">清空<span ng-if="isDelLoading">中...</span></button>
                                        </span>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th width="20%">活动ID</th>
                                        <th width="70%">名称</th>
                                        <th width="10%">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="row in attachedSales">
                                        <td ng-bind="row.id"></td>
                                        <td ng-bind="row.name"></td>
                                        <td><button class="btn btn-xs btn-default" ng-click="removeAttach(row)">删除</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-8 sm-padding-col" ng-if="isSalesScope()">
                                <label for="ipt_1" class="col-md-2 control-label sm-padding-col" style="width:12.5%">限目的地</label>
                                <div class="col-md-10 sm-padding-col form-item ng-scope" style="width:87.5%">
                                    <select
                                            ng-model="detail.mddto_ids"
                                            class="form-control"
                                            placeholder="请填写城市/国家级目的地"
                                            mfw-select
                                            multiple
                                            optionlabel="detail.optionlabel"
                                            url="/ajax.php?sModule=common&sController=options&sAction=suggest"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-12 sm-padding-col">
                                <label for="ipt_1" class="col-md-1 control-label sm-padding-col">自定文案</label>
                                <div class="col-md-11 sm-padding-col">
                                    <input type="text" ng-model="detail.custom_desc" placeholder="限xxxx产品" class="form-control" ng-required="detail.used_sales_column == 2 || detail.used_sales_column == 3">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">

                            <div ng-hide="1==1" class="form-group col-md-4 sm-padding-col" >
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">补贴方</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select ng-options="o.value as o.label for o in distributor"
                                            class="form-control" ng-model="detail.distributor" ng-disabled="detail.id">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-12 sm-padding-col">
                                <label for="ipt_1" class="col-md-1 control-label sm-padding-col">备注说明</label>
                                <div class="col-md-11 sm-padding-col">
                                    <input ng-model="detail.description" type="text" class="form-control" ng-required="detail.send_purpose == ka_ota_supplement">
                                </div>
                            </div>


                            <div class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">是否测试</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select class="form-control" ng-model="detail.is_test" required>
                                        <option ng-repeat="(value, name) in is_test" value="{{value}}">{{name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">需要预警</label>
                                <div class="col-md-9 sm-padding-col">
                                    <select class="form-control" ng-model="detail.is_need_warning" required>
                                        <option ng-repeat="(value, name) in is_need_warning" value="{{value}}">{{name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div ng-show="auth.identityIs('马蜂窝员工')" class="form-group col-md-4 sm-padding-col">
                                <label for="ipt_1" class="col-md-3 control-label sm-padding-col">到账短信</label>
                                <div class="col-md-9 sm-padding-col multiselect-col">
                                    <select class="form-control" ng-model="detail.allow_send_arrival_sms"
                                            ng-options="op.value as op.name for op in allow_send_arrival_sms"></select>
                                </div>
                            </div>
                            <div class="form-group col-md-12 sm-padding-col" >
                                <label for="ipt_1" class="col-md-2 control-label sm-padding-col">跳转链接/shareJump</label>
                                <div class="col-md-10 sm-padding-col">
                                    <input ng-model="detail.relation_url" placeholder="为空默认跳转逻辑" type="text" class="form-control" id="ipt_1">
                                </div>
                                <div class="col-md-10 sm-padding-col col-md-push-2">
                                    <span>备注: 填写h5链接或者sharejump,商城列表页这中原生页，可以配置目的地参数，精准跳转至具体目的地列表。</span>
                                    <br>
                                    <a href="https://admin.mafengwo.cn/app_client/index.html#/mfwapp/sharejump?sort=type&module=Sales">sharejump获取地址</a>
                                </div>
                            </div>



                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="view" class="modal" ng-show="showModalview==1" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h4 class="w_p_50 pull-left modal-title">查看线下优惠券  <i>{{code_load_num}}</i></h4>
                <button class="btn btn-warning btn-sm pull-right"
                        ng-click="closeModal('view')"><i class="fa fa-times"></i> 关闭</button>
            </div>
            <div class="modal-body">
                <a ng-repeat="v in coupon_total_arr" class="btn btn-warning btn-xs" ng-click="loadCode($index, 0)" href="javascript:;">
                    <i class="fa fa-desktop"></i> {{$index>0 ? $index+'0001' : '1' }}-{{v}}
                </a>
                <a class="btn btn-warning btn-xs" ng-click="exportCodes('{{coupon_code_list[0].coupon_id}}')" href="javascript:void(0);"><i class="fa fa-filter"></i>导出全部优惠码</a>
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>优惠码</th>
                        <th>UID</th>
                        <th>订单号</th>
                        <th>使用状态</th>
                        <th>发送状态</th>
                        <th>创建时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="one in coupon_code_list">
                        <td>{{one.id}}</td>
                        <td>{{one.code}}</td>
                        <td>{{one.uid}}</td>
                        <td>{{one.order_id}}</td>
                        <td>{{one.flag==1?'未使用':(one.flag==2?'已使用':(one.flag==3?'已过期':(one.flag==4?'作废':'未知')))}}</td>
                        <td>{{one.is_sent==1?'已发送':'未发送'}}</td>
                        <td>{{one.ctime}}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>


<div id="generate" class="modal" ng-show="showModalgenerate==1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h4 class="w_p_50 pull-left modal-title">生成线下优惠券 </h4>
                <button class="btn btn-warning btn-sm pull-right"
                        ng-click="closeModal('generate')"><i class="fa fa-times"></i> 关闭</button>
            </div>
            <div class="modal-body">

                优惠券数量共{{code_total}} 已生成{{generate_total}}
                <span ng-if="code_total>generate_total">
                         <a ng-if="generate_stop==0" class="btn btn-info btn-xs" ng-click="rsync_generate_toggle()" href="javascript:;">开始生成</a>
                         <a ng-if="generate_stop==1" class="btn btn-info btn-xs" ng-click="rsync_generate_toggle()" href="javascript:;">继续生成</a>
                         <a ng-if="generate_stop==2" class="btn btn-info btn-xs" ng-click="rsync_generate_toggle()" href="javascript:;">停止</a>
                        </span>



            </div>
        </div>
    </div>
</div>


<div id="statistics" class="modal" ng-show="showModalstatistics==1" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h4 class="pull-left modal-title">优惠券数据统计 <i>总计发送：{{statistics_send_total}}  总计使用：{{statistics_used_total}} 转换率：{{statistics_used_scale}}%</i></h4>
                <button class="btn btn-warning btn-sm pull-right"
                        ng-click="closeModal('statistics')"><i class="fa fa-times"></i> 关闭</button>
            </div>

            <div class="modal-body">
                <div style="display: block;">
                    <div class="form-group col-md-4 sm-padding-col">
                        <label for="ipt_1" class="col-md-3 control-label sm-padding-col" style="line-height:30px;">开始时间</label>
                        <div class="col-md-9 sm-padding-col">
                            <input placeholder="开始时间" ng-model="exportParam.sExportStartTime" mfw-date-time-picker="YYYY-MM-DD" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-4 sm-padding-col">
                        <label for="ipt_1" class="col-md-3 control-label sm-padding-col" style="line-height:30px;">结束时间</label>
                        <div class="col-md-9 sm-padding-col">
                            <input placeholder="结束时间" ng-model="exportParam.sExportEndTime" mfw-date-time-picker="YYYY-MM-DD" type="text" class="form-control">
                        </div>
                    </div>
                    <a class="btn btn-primary btn-sm" ng-click="exportOrderInfo()" ng-disabled="listLoading" href="javascript:void(0);"><i class="fa fa-filter"></i>导出订单明细</a>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>日期</th>
                        <th>共发送</th>
                        <th>共使用(支付成功)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="one in statistics_list">
                        <td>{{one.date}}</td>
                        <td>{{one.send_total}}</td>
                        <td>{{one.used_total}}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>



<div id="departmentStatistics" class="modal" ng-show="showModaldepartmentStatistics==1" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h4 class="w_p_50 pull-left modal-title">部门按天数据统计</h4>
                <button class="btn btn-warning btn-sm pull-right"
                        ng-click="closeModal('departmentStatistics')"><i class="fa fa-times"></i> 关闭</button>
            </div>
            <div class="panel-body">
                <form class="form-inline form-search">

                    <select class="form-control" ng-change="runDepartmentStatistics()" ng-model="searchDepartmentParam.department">
                        <option value="">全部</option>
                        <option ng-repeat="(value, label) in department" value="{{value}}">{{label}}</option>
                    </select>

                    <i class="fa fa-spinner fa-spin" ng-if="departmentStatisticsing"></i>
                </form>

            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>部门</th>
                        <th>月份</th>
                        <th>共发送</th>
                        <th>共发送金额</th>
                        <th>共使用</th>
                        <th>共使用金额</th>
                        <th ng-if="searchDepartmentParam.department">订单详情</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="one in departmentStatistics_list">
                        <td ng-if="searchDepartmentParam.department">{{department[searchDepartmentParam.department]}}</td>
                        <td ng-if="!searchDepartmentParam.department">全部</td>
                        <td>{{one.month}}</td>
                        <td>{{one.send_total}}</td>
                        <td>{{one.send_money_total}}</td>
                        <td>{{one.used_total}}</td>
                        <td>{{one.used_money_total}}</td>
                        <td  ng-if="searchDepartmentParam.department">
                            <a class="btn btn-danger btn-xs" ng-click="departmentStatisticsOrder(one)" href="javascript:;">订单详情</a>
                        </td>

                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<div id="departmentStatisticsById" class="modal" ng-show="showModaldepartmentStatisticsById==1" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h4 class="w_p_50 pull-left modal-title">部门按id数据统计</h4>
                <button class="btn btn-warning btn-sm pull-right"
                        ng-click="closeModal('departmentStatisticsById')"><i class="fa fa-times"></i> 关闭</button>
            </div>
            <div class="panel-body">
                <form class="form-inline form-search">

                    <select class="form-control" ng-change="runDepartmentStatisticsById()" ng-model="searchDepartmentParam.department">
                        <option ng-repeat="(value, label) in department" value="{{value}}">{{label}}</option>
                    </select>

                    <i class="fa fa-spinner fa-spin" ng-if="departmentStatisticsByIding"></i>
                </form>

            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>部门</th>
                        <th>id</th>
                        <th>优惠券名称</th>
                        <th>价格</th>
                        <th>上限数量</th>
                        <th>金额下限</th>
                        <th>到期时间</th>
                        <th>共发送</th>
                        <th>共发送金额</th>
                        <th>共使用</th>
                        <th>共使用金额</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="one in departmentStatisticsbyid_list">
                        <td ng-if="searchDepartmentParam.department">{{department[searchDepartmentParam.department]}}</td>
                        <td ng-if="!searchDepartmentParam.department">全部</td>
                        <td>{{one.coupon_id}}</td>
                        <td>{{one.coupon_name}}</td>
                        <td>{{one.coupon_price}}</td>
                        <td>{{one.coupon_num}}</td>
                        <td>{{one.coupon_used_price}}</td>
                        <td>{{one.coupon_end_time}}</td>
                        <td>{{one.send_total}}</td>
                        <td>{{one.send_money_total}}</td>
                        <td>{{one.used_total}}</td>
                        <td>{{one.used_money_total}}</td>

                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<div id="departmentStatisticsOrder" class="modal" ng-show="showModaldepartmentStatisticsOrder==1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h4 class="w_p_50 pull-left modal-title">订单列表</h4>
                <button class="btn btn-warning btn-sm pull-right"
                        ng-click="closeModal('departmentStatisticsOrder')"><i class="fa fa-times"></i> 关闭</button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>uid</th>
                        <th>订单号</th>
                        <th>使用时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="one in departmentStatisticsOrder_list">
                        <td>{{one.uid}}</td>
                        <td>{{one.order_id}}</td>
                        <td>{{one.mtime}}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>


<div id="helpId" class="modal" ng-show="showModalhelpId==1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h4 class="w_p_50 pull-left modal-title">优惠券使用说明</h4>
                <button class="btn btn-warning btn-sm pull-right"
                        ng-click="closeModal('helpId')"><i class="fa fa-times"></i> 关闭</button>
            </div>
            <div class="modal-body">

                <dl>
                    <dt>什么是优惠券？</dt>
                    <dd>
                        优惠券是马蜂窝发行并认可的购物券，可用于在马蜂窝商城付款的产品订单，可抵扣面值相应金额的费用。通常以全场通用、满额减免等形式出现。优惠券不兑现，不找零;目前优惠券只适用商城频道与酒店频道
                    </dd>
                    <dt>怎样获得优惠券？</dt>
                    <dd>
                        马蜂窝商城手机客户端、网站、马蜂窝良品微信公众号会不定期推出活动赠发优惠券。
                    </dd>
                    <dt>如何使用优惠券？</dt>
                    <dd>
                        在网站和手机端付款时，都可以选择使用优惠券（可在“我的优惠券”里查看）；新添加一个优惠券时，输入优惠券密码通过验证后，抵扣相应的金额。请在有效期前使用，过期无效。
                    </dd>

                    <dt>发放方式</dt>
                    <dd>
                        <ul>
                            <li>系统触发:为实时生成,多用于活动领券与程序发放</li>
                            <li>线下发放:为后台批量生成,可直接复制或导出券码,多用于渠道合作</li>
                        </ul>
                    </dd>
                </dl>



            </div>
        </div>
    </div>
</div>


<div id="getLinkById" class="modal" ng-show="showModalgetLinkById==1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h4 class="w_p_50 pull-left modal-title">生成领取链接</h4>
                <button class="btn btn-warning btn-sm pull-right"
                        ng-click="closeModal('getLinkById')"><i class="fa fa-times"></i> 关闭</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12 sm-padding-col" >
                        <label for="ipt_1" class="col-md-2 control-label sm-padding-col">优惠券id</label>
                        <div class="col-md-3 sm-padding-col">
                            <input ng-model="detail.id" type="text" class="form-control" id="ipt_1" required>
                        </div>
                    </div>
                    <div class="form-group col-md-12 sm-padding-col">
                        <label for="ipt_1" class="col-md-2 control-label sm-padding-col">跳转链接</label>
                        <div class="col-md-9 sm-padding-col">
                            <input ng-model="detail.url" type="text" class="form-control" id="ipt_1" required>
                        </div>
                    </div>

                    <div class="form-group col-md-12 sm-padding-col">

                        <div class="col-md-3 sm-padding-col"></div>
                        <div class="col-md-3 sm-padding-col">
                            <a class="btn btn-info" ng-click="generateLink(detail)" href="javascript:;">
                                生成
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row" ng-if="coupon_link">
                    <div class="col-md-1 sm-padding-col"></div>
                    <div class="col-md-11 sm-padding-col">领取链接：<a href="{{coupon_link}}" target="_blank">{{coupon_link}}</a></div>
                </div>

            </div>
        </div>
    </div>
</div>
{/literal}
{literal}
<script type="text/ng-template" id="download.html">
    <div class="modal-header">
        <h3 class="modal-title">导出EXCEL</h3>
    </div>
    <div class="modal-body">
        <uib-progressbar animate="false" value="showParam.percent" type="{{showParam.type}}"></uib-progressbar>
        <p class="text-center">{{showParam.text}}<span ng-if="showParam.total>0"> ({{showParam.percent}}%)</span></p>
    </div>
    <div class="modal-footer" ng-if="showParam.status!='finished'">
        <button class="btn btn-primary" ng-click="download()" ng-disabled="showParam.status!='loaded_index'">开始下载</button>
        <button class="btn btn-default" ng-click="close()">取消</button>
    </div>
    <div class="modal-footer" ng-if="showParam.status=='finished'">
        <button class="btn btn-success" ng-click="close()">关闭</button>
    </div>
</script>
{/literal}