const app = getApp()
// console.log(app)
const time = require('../../utils/util.js')
// console.log(time)
Page({

  /**
   * 页面的初始数据
   */
  data: {
    num: 1,
    minusStatus: 'disable',
    price: 0.01,
    numprice: 0.01,
    openid:'',
    id:'',
    latitude:null,
    longitude:null
  },

  //事件处理函数，打开地图
  openLocation: function () {
    // console.log(this.data.latitude)
    var that = this
    wx.openLocation({
      latitude: that.data.latitude,
      longitude: that.data.longitude,
    })
  },

  //获取当前位置
  getLocation: function (e) {
    wx.getLocation({
      success: function (res) {
        console.log(res);
      },
    })
  },

  /*点击减号*/
  bindMinus: function () {
    var num = this.data.num;
    if (num > 1) {
      num--;
    }
    var minusStatus = num > 1 ? 'normal' : 'disable';
    this.setData({
      num: num,
      numprice: num * this.data.price,
      minusStatus: minusStatus
    })
  },

  /*点击加号*/
  bindPlus: function () {
    var num = this.data.num;
    num++;
    var minusStatus = num > 1 ? 'normal' : 'disable';
    this.setData({
      num: num,
      numprice: num * this.data.price,
      minusStatus: minusStatus
    })
  },

  /*数量输入框事件*/
  bindManual: function (e) {
    var num = e.detail.value;
    var minusStatus = num > 1 ? 'normal' : 'disable';
    this.setData({
      num: num,
      numprice: num * this.data.price,
      minusStatus: minusStatus
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
      // console.log(options)
    var id = options.id
    var that = this //注意一定要定义变量，不然下面回调函数用setdata会报错
    var t = time
    // var d = 1535524860
    // console.log(t.formatTimeTwo(d, 'Y/M/D h:m:s'))
    wx.request({
      url: 'https://xpiao.ytsshop.com/api/Jieshao.php?id='+id,
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        // console.log(res.data[0]);
        //解释json对象才能取值
        var s = JSON.parse(res.data[0].expiretime)
        var start = s.start
        var end = s.end
        var str = res.data[0].latlng
        // 字符串分割成数组
        var arr = str.split(',')
            // console.log(arr[0])
        // console.log(t.formatTimeTwo(start, 'Y/M/D'))
        // 把获取到的数据发给视图
        that.setData({
          id: res.data[0].id,
          title: res.data[0].title,
          fee: res.data[0].fee,
          content : res.data[0].content,
          placedetail: res.data[0].placedetail,
          start: t.formatTimeTwo(start, 'Y/M/D'),
          end: t.formatTimeTwo(start, 'Y/M/D'),
          latitude: parseInt(arr[0]),
          longitude:parseInt(arr[1])
        })
      }
    })
  },

    // 生成订单后跳转订单详情页面
  weixinpay:function(){
    var that = this
      // 生成订单
      wx.request({
        url: 'https://xpiao.ytsshop.com/api/order.php',
        data:{
            actid:that.data.id,
            openid: app.globalData.openid,
            total: that.data.num,
            fee:that.data.price,

        },
        success:function(res){
             if(res.data==1){
              //  console.log('创建订单成功')
              //  console.log(that.data.id)
                wx.navigateTo({
                  url: '../../pages/order/order?actid=' + that.data.id,
                })
              }else{
                console.log('创建订单失败')
              }
            }
      })
  
  },

  

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
    
  }
})