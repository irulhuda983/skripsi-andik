function __vite__mapDeps(indexes) {
  if (!__vite__mapDeps.viteFileDeps) {
    __vite__mapDeps.viteFileDeps = ["assets/AreaChart-OlXcbY2a.js","assets/app-Dr-oWWLk.js"]
  }
  return indexes.map((i) => __vite__mapDeps.viteFileDeps[i])
}
import{r,y as x,c as f,a as e,t as l,h as o,m as a,o as u,z as p,A as v,_ as b}from"./app-Dr-oWWLk.js";import{c as n}from"./createLucideIcon-BhFW6A1j.js";/**
 * @license lucide-vue-next v0.352.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const m=n("ActivityIcon",[["path",{d:"M22 12h-4l-3 9L9 3l-3 9H2",key:"d5dnw9"}]]);/**
 * @license lucide-vue-next v0.352.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const y=n("HistoryIcon",[["path",{d:"M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8",key:"1357e3"}],["path",{d:"M3 3v5h5",key:"1xhq8a"}],["path",{d:"M12 7v5l4 2",key:"1fdv2h"}]]);/**
 * @license lucide-vue-next v0.352.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const w=n("ListTodoIcon",[["rect",{x:"3",y:"5",width:"6",height:"6",rx:"1",key:"1defrl"}],["path",{d:"m3 17 2 2 4-4",key:"1jhpwq"}],["path",{d:"M13 6h8",key:"15sg57"}],["path",{d:"M13 12h8",key:"h98zly"}],["path",{d:"M13 18h8",key:"oe0vm4"}]]),k={class:"w-full box-border"},g={class:"w-full box-border flex items-center flex-col lg:flex-row justify-between gap-3 mb-5"},M={class:"w-full lg:w-80 h-24 rounded border border-border box-border p-4 flex items-center"},I={class:"w-full"},j=e("h5",{class:"text-[10px] text-primary/40"},"Jenis Vaksin",-1),L={class:"text-[40px] font-semibold"},A={class:"w-20 flex-none flex items-center justify-center"},V={class:"w-full lg:w-80 h-24 rounded border border-border box-border p-4 flex items-center"},E={class:"w-full"},P=e("h5",{class:"text-[10px] text-primary/40"}," Penggunaan Vaksin ",-1),T={class:"text-[40px] font-semibold"},D={class:"w-20 flex-none flex items-center justify-center"},H={class:"w-full lg:w-80 h-24 rounded border border-border box-border p-4 flex items-center"},B={class:"w-full"},N=e("h5",{class:"text-[10px] text-primary/40"}," Histori Peramalan ",-1),S={class:"text-[40px] font-semibold"},q={class:"w-20 flex-none flex items-center justify-center"},z={class:"w-full box-border border border-border rounded"},C=e("h1",{class:"mb-2 box-border p-3 text-primary/60"}," Grafik Penggunaan Vaksin ",-1),K={__name:"Index",setup(G){const c=p(()=>b(()=>import("./AreaChart-OlXcbY2a.js"),__vite__mapDeps([0,1]))),{notify:h}=v(),d=r(!1),s=r({history:0,transaksi:0,vaksin:0}),i=r([]),_=async()=>{d.value=!0;try{const{data:t}=await axiosInstance({url:"/dashboard/chart",method:"GET"});s.value=t.jumlah,i.value=t.chart,console.log(t)}catch(t){t.response.status==401?(localStorage.removeItem("TOKEN"),location.reload()):h({text:"Faliled to add, Server is Maintenent",type:"error",duration:2e3})}finally{d.value=!1}};return x(()=>{_()}),(t,O)=>(u(),f("div",k,[e("div",g,[e("div",M,[e("div",I,[j,e("div",L,l(s.value.vaksin),1)]),e("div",A,[o(a(w),{class:"w-12 h-12 text-blue-500"})])]),e("div",V,[e("div",E,[P,e("div",T,l(s.value.transaksi),1)]),e("div",D,[o(a(m),{class:"w-12 h-12 text-teal-500"})])]),e("div",H,[e("div",B,[N,e("div",S,l(s.value.history),1)]),e("div",q,[o(a(y),{class:"w-12 h-12 text-violet-500"})])])]),e("div",z,[C,o(a(c),{class:"lg:col-span-4",data:i.value},null,8,["data"])])]))}};export{K as default};
