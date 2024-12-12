var checkConfig = {
    /*按钮文字*/
    finalBtnText: '确定',
    /*每一步各状态描述*/
    checkList: [
        {
            'unchecked':'是否填写了网站介绍',
            'checking':'是否填写了网站介绍',
            'checked':'是否填写了网站介绍'
        },
        {
            'unchecked':'是否设置了网站图标',
            'checking':'是否设置了网站图标',
            'checked':'是否设置了网站图标'
        },
        {
            'unchecked':'是否上传了分享封面图',
            'checking':'是否上传了分享封面图',
            'checked':'是否上传了分享封面图'
        },
        {
            'unchecked':'是否最新版网站系统',
            'checking':'是否最新版网站系统',
            'checked':'是否最新版网站系统'
        },
        {
            'unchecked':'是否进行了SEO设置',
            'checking':'是否进行了SEO设置',
            'checked':'是否进行了SEO设置'
        },
        {
            'unchecked':'是否发布10篇文章',
            'checking':'是否发布10篇文章',
            'checked':'是否发布10篇文章'
        },
        {
            'unchecked':'是否开启了站点地图',
            'checking':'是否开启了站点地图',
            'checked':'是否开启了站点地图'
        }
    ],
    /*是否显示按钮*/
    isShowFinalBtn: false,
    /*执行结束后的回调函数*/
    callback: function () {
        // alert('检测对象真的是一个人才！');
        $('#autoCheckModal').remove();
        $('#pop-con1').hide();
        $('#pop-con2').show();
    },
    /*是否自动执行回调函数*/
    isAutoRunCallback: true,
    /*每一步的时间间隔*/
    timeGap: 1000

};
