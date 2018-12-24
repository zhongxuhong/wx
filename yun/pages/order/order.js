const app = getApp()
const time = require('../../utils/util.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    orderid:'', 
    id:'',
    actid:''  
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    // console.log(options)
    // console.log(app.globalData.openid)
    var that = this
    var t = time
    var id = options.id
    var actid = options.actid
       if(actid){
         wx.request({
           url: 'https://xpiao.ytsshop.com/api/indexorder.php',
           data: {
             openid: app.globalData.openid,
             actid: actid
           },
           success: function (res) {
             // console.log(res.data)
             that.setData({
               proname: res.data.proname,
               orderid: res.data.id,
               createtime: t.formatTimeTwo(res.data.createtime, 'Y/M/D h:m:s'),
               placedetail: res.data.placedetail,
               total: res.data.total,
               fee: res.data.fee,
               numfee: res.data.fee * res.data.total
             })
           }
         })
       }else{
         wx.request({
           url: 'https://xpiao.ytsshop.com/api/orderDetails.php',
           data: {
             openid: app.globalData.openid,
             id: id
           },
           success: function (res) {
             // console.log(res.data)
             that.setData({
               proname: res.data.proname,
               orderid: res.data.id,
               createtime: t.formatTimeTwo(res.data.createtime, 'Y/M/D h:m:s'),
               placedetail: res.data.placedetail,
               total: res.data.total,
               fee: res.data.fee,
               numfee: res.data.fee * res.data.total
             })
           }
         })
       }
  },

  pay:function(e){
    var that=this
    wx.request({
        url: 'https://xpiao.ytsshop.com/api/WeiXinPay.php',//先统一下单
        data:{
          openid:app.globalData.openid,
          numprice:this.data.numprice
        },
        success:function(res){
          // console.log(res.data)
          //调起小程序微信支付
          wx.requestPayment({
            'timeStamp': res.data.timeStamp,
            'nonceStr': res.data.nonceStr,
            'package': res.data.package,
            'signType': 'MD5',
            'paySign': res.data.paySign,
            'success': function (res) {
              console.log(that.data.orderid)
                wx.request({
                  url: 'https://xpiao.ytsshop.com/api/updateOrder.php',
                  data:{
                    orderid:that.data.orderid
                  },
                  success:function(res){
                    console.log(res.data)
                    if(res.data==1){
                      console.log('支付成功')
                      wx.switchTab({
                        url: '../bill/bill',
                        success: function (e) {
                          var page = getCurrentPages().pop();
                          if (page == undefined || page == null) return;
                          page.onLoad();
                        } 
                      })
                    }
                  }
                })
            },
            'fail': function (res) {
                console.log('支付失败')
            }
          })
        }
      })
  }

  
})