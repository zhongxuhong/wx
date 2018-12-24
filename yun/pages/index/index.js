const app = getApp()

Page({
  data: {
      num: 1,
      minusStatus: 'disable',
      price:0.01,
      numprice:0.01
  },
  //事件处理函数，打开地图
  openLocation: function (e) {
    wx.openLocation({
      latitude: 28.3743549743496,
      longitude: 110.98871257003786,
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

  // 景区介绍
  attractions:function(){
      wx.navigateTo({
        url: '../index/attractions/attractions',
      })
  },
  //景区资讯
  news:function(){
    // console.log('我是新闻资讯')
    wx.navigateTo({
      url: '../index/news/news',
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  // onLoad: function (options) {
  //   console.log(options)
  // },
  imgbtn: function(e){
   var id=e.currentTarget.id
      //  console.log(id)
    wx.navigateTo({
      url: '../detail/detail?id=' + id,
    })
  },
// 调起微信小程序转发按钮
  onShareAppMessage: function () {
    return {
      title: '中安运城票务系统',
      desc: '云台山茶旅集团!',
      path: 'pages/index/index'
    }
  }




})
