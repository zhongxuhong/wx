var app = getApp()
const time = require('../../utils/util.js')
Page({
  data: {
    currentTab: 0,
    modalHidden: true
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
       console.log(options)
        if(options){
          this.orders(options)
        }else{
          this.orders(0)  
        }
         
  },
  onShow:function(op){
      // console.log(op)
  },
  //滑动切换
  swiperTab: function (e) {
    var that = this;
    var current = e.detail.current
    this.orders(current)
    that.setData({
      currentTab: current
    });
  },
  //点击切换
  clickTab: function (e) {
    // console.log(e)
    var that = this;
    var current = e.target.dataset.current
    if (this.data.currentTab === current) {
      return false;
    } else {
      this.orders(current)
      that.setData({
        currentTab: current
      })
    }
  },
  

  orders:function(pare){
    var that = this
    var t = time
    var arr = []
    var paytime = []
    var start = []
    var end = []
    var closetime = []
    var scantime = []
    var createtime = []
    wx.request({
      url: 'https://xpiao.ytsshop.com/api/bill.php',
      data: {
        openid: app.globalData.openid,
        current: pare
      },
      success: function (res) {
        arr = res.data
        // console.log(arr)
        for (var i = 0; i < arr.length; i++) {
          paytime[i] = t.formatTimeTwo(arr[i].paytime, 'Y/M/D h:m:s')
          var s = JSON.parse(arr[i].expiretime)
          start[i] = t.formatTimeTwo(s.start, 'Y/M/D')
          end[i] = t.formatTimeTwo(s.end, 'Y/M/D')
          arr[i].paytime = paytime[i]
          arr[i].start = start[i]
          arr[i].end = end[i]
          closetime[i] = t.formatTimeTwo(arr[i].closetime, 'Y/M/D h:m:s')
          arr[i].closetime = closetime[i]
          createtime[i] = t.formatTimeTwo(arr[i].createtime, 'Y/M/D h:m:s')
          arr[i].createtime = createtime[i]
          scantime[i] = t.formatTimeTwo(arr[i].scantime, 'Y/M/D h:m:s')
          arr[i].scantime = scantime[i]
        }
        // console.log(arr)
        that.setData({
          array: res.data
        })
      }
    })
  },

  orderpay:function(e){
    var that = this
    wx.navigateTo({
      url: '../../pages/order/order?id=' + e.currentTarget.id,
    })
  },
  // 末付款删除订单
  delorder:function(e){
    var that = this
    // console.log(e)
    wx.request({
       url: 'https://xpiao.ytsshop.com/api/updateOrder.php',
      data: {
        id: e.currentTarget.id
      },
      success: function (res) {
        // console.log(res)
        if (res.data == 1) {
          // console.log('删除成功')
          //删除重新加载页面
          that.onLoad(2);
        }
      }
    })
  },
  // 已使用删除订单
  delorders: function (e) {
    var that = this
    // console.log(e)
    wx.request({
      url: 'https://xpiao.ytsshop.com/api/updateOrder.php',
      data: {
        id: e.currentTarget.id
      },
      success: function (res) {
        // console.log(res)
        if (res.data == 1) {
          console.log('删除成功')
          console.log(res.data)
          //删除重新加载页面
          that.onLoad(1);
        }
      }
    })
  },

  /**
    * 弹窗
    */
  showDialogBtn: function (e) {
    this.setData({
      showModal: true,
      orderid: e.currentTarget.id,
      openid: app.globalData.openid
    })
  },
  /**
   * 弹出框蒙层截断touchmove事件
   */
  preventTouchMove: function () {
  },
  /**
   * 隐藏模态对话框
   */
  hideModal: function () {
    this.setData({
      showModal: false
    });
  },
  /**
   * 对话框取消按钮点击事件
   */
  onCancel: function () {
    this.hideModal();
    this.onLoad()
  },
  /**
   * 对话框确认按钮点击事件
   */
  onConfirm: function () {
    this.hideModal();
    this.onLoad()
  }

})
