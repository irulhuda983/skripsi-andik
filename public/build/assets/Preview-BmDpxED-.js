function __vite__mapDeps(indexes) {
  if (!__vite__mapDeps.viteFileDeps) {
    __vite__mapDeps.viteFileDeps = ["assets/AreaChart-OlXcbY2a.js","assets/app-Dr-oWWLk.js"]
  }
  return indexes.map((i) => __vite__mapDeps.viteFileDeps[i])
}
import{s as ae,r as R,b as se,y as te,c as h,a as r,h as s,x as t,m as e,t as o,w as I,C as F,d as $,o as p,D as i,F as O,E as U,z as re,j as le,q as oe,A as ne,_ as de}from"./app-Dr-oWWLk.js";import{u as G,a as ie,b as ce,c as ue,d as _e,e as L,_ as fe}from"./forecast-B_fmerBp.js";import{_ as q}from"./index-DJCjot5l.js";import{_ as k,b as me,e as _,c as pe,d as be,a as he}from"./TableRow-HQMrNe_T.js";import{T as xe}from"./trash-2-cPU03S0_.js";import{S as ve}from"./save-BnBt_oDL.js";import"./createLucideIcon-BhFW6A1j.js";const ge={class:"w-full box-border"},ke={class:"w-full relative overflow-x-auto shadow-md sm:rounded-lg border border-border bg-card text-card-foreground shadow"},ye={class:"box-border px-6 py-3 border-b border-border flex flex-col lg:flex-row lg:justify-between lg:items-center gap-2"},we={class:"flex items-center justify-center gap-2"},Fe=r("div",{class:"text-muted-foreground"}," Tampilkan : ",-1),$e={class:"rounded border border-border px-1.5 lg:px-3 py-2 text-xs flex flex-col lg:flex-row item-center justify-center gap-2 text-xs"},Ve={class:"flex items-center justify-center gap-2"},Pe=r("div",{class:"text-muted-foreground"},"Vaksin :",-1),Ce=r("div",{class:"w-full lg:w-px h-px lg:h-4 bg-border"},null,-1),je={class:"flex items-center justify-center gap-2"},Ae=r("div",{class:"text-muted-foreground"},"Alpha :",-1),Be=r("div",{class:"w-full lg:w-px h-px lg:h-4 bg-border"},null,-1),Ee={class:"flex items-center justify-center gap-2"},Se=r("div",{class:"text-muted-foreground"},"Bulan :",-1),Me={class:"flex items-center justify-center gap-2"},Te={class:"flex items-center"},ze=r("div",{class:"text-xs"},"Buang",-1),De={class:"flex items-center"},Ne=r("div",{class:"text-xs"},"Simpan Hasil Peramalan",-1),Re={key:0,class:"relative overflow-x-auto w-full text-xs"},Ie=r("div",{class:"sr-only"},"Rata - rata",-1),Oe={key:1,class:""},Ue=r("h1",{class:"mb-2 box-border p-3"}," Grafik Perbandingan Peramalan ",-1),Ge={class:"mb-2 box-border p-3 flex gap-3 capitalize"},Le={class:"font-semibold"},ea={__name:"Preview",setup(qe){const H=re(()=>de(()=>import("./AreaChart-OlXcbY2a.js"),__vite__mapDeps([0,1]))),y=le(),K=G(),{setForecastData:J}=G(),{singgleForecast:l,multiForecast:He}=ae(K),{notify:V}=ne(),Q=R(["no.","bulan","tahun","aktual","forecast","MAD","MSE","MAPE"]),x=R("detail"),w=se([{name:"aktual",data:[]},{name:"forecast",data:[]}]),W=()=>{l.value==null?y.push({name:"peramalan"}):X()},X=()=>{var f;const m=(f=l.value)==null?void 0:f.hasil.detailForecast;console.log(m),m&&m.forEach(n=>{let c=`${ee(n.namaBulan)} ${n.tahun}`;w[0].data.push({x:c.toUpperCase(),y:n.actual}),w[1].data.push({x:c.toUpperCase(),y:n.forecast})})},Y=async()=>{var m,f;try{const n={idVaksin:l.value.idVaksin,alpha:l.value.alpha,periode:l.value.periode,bulan:l.value.bulan,tahun:l.value.tahun},{data:c}=await axiosInstance({url:"/peramalan",method:"POST",data:n});V({text:"Berhasil tambah peramalan",type:"success",duration:2e3}),y.push({name:"peramalan"})}catch(n){if(((m=n==null?void 0:n.response)==null?void 0:m.status)==401)localStorage.removeItem("TOKEN"),location.reload();else if(((f=n==null?void 0:n.response)==null?void 0:f.status)==422){const c=n.response.data.errors;errors.idVaksin=c.idVaksin?c.idVaksin[0]:null,errors.range=c.range?c.range[0]:null,errors.periode=c.periode?c.periode[0]:null,errors.alpha=c.alpha?c.alpha[0]:null}else console.log(n),V({text:"Faliled to add, Server is Maintenent",type:"error",duration:2e3})}},Z=()=>{J(null,"singgle"),y.push({name:"peramalan"})},ee=m=>{const f=m;if(m.length<3)return f;const c=m.split("");let b=[];return c.forEach((v,g)=>{g<3&&b.push(v)}),b.join("")};return te(()=>{W()}),(m,f)=>{var n,c,b,v,g,P,C,j,A,B,E,S,M,T;return p(),h("div",ge,[r("div",ke,[r("div",ye,[r("div",null,[s(e(fe),{modelValue:x.value,"onUpdate:modelValue":f[0]||(f[0]=a=>x.value=a),class:"border-border"},{default:t(()=>[s(e(ie),null,{default:t(()=>[r("div",we,[Fe,s(e(ce),{class:"mr-1"})])]),_:1}),s(e(ue),null,{default:t(()=>[s(e(_e),null,{default:t(()=>[s(e(L),{value:"detail"},{default:t(()=>[i(" Detail ")]),_:1}),s(e(L),{value:"grafik"},{default:t(()=>[i(" Grafik ")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),r("div",$e,[r("div",Ve,[Pe,r("div",null,o((n=e(l))==null?void 0:n.vaksin),1)]),Ce,r("div",je,[Ae,r("div",null,o((c=e(l))==null?void 0:c.alpha),1)]),Be,r("div",Ee,[Se,r("div",null,o((b=e(l))==null?void 0:b.bulan)+"/"+o((v=e(l))==null?void 0:v.tahun),1)])]),r("div",Me,[r("div",Te,[s(e(q),{variant:"outline",size:"sm",onClick:f[1]||(f[1]=I(a=>Z(),["prevent"]))},{default:t(()=>[s(e(xe),{class:"w-4 h-4 mr-3"}),ze]),_:1})]),r("div",De,[s(e(q),{variant:"outline",size:"sm",onClick:f[2]||(f[2]=I(a=>Y(),["prevent"]))},{default:t(()=>[s(e(ve),{class:"w-4 h-4 mr-3"}),Ne]),_:1})])])]),x.value=="detail"?(p(),h("div",Re,[((g=e(l))==null?void 0:g.hasil.detailForecast.length)>0?(p(),F(e(be),{key:0},{default:t(()=>[s(e(me),{class:"bg-muted/50"},{default:t(()=>[s(e(k),null,{default:t(()=>[(p(!0),h(O,null,U(Q.value,a=>(p(),F(e(he),{class:oe(["capitalize border-r border-border",a=="MAD"||a=="MSE"||a=="MAPE"?"text-end":"text-center"])},{default:t(()=>[i(o(a),1)]),_:2},1032,["class"]))),256))]),_:1})]),_:1}),s(e(pe),null,{default:t(()=>[(p(!0),h(O,null,U(e(l).hasil.detailForecast,(a,d)=>(p(),F(e(k),null,{default:t(()=>[s(e(_),{class:"border-r border-border"},{default:t(()=>[i(o(d+1)+".",1)]),_:2},1024),s(e(_),{class:"border-r border-border capitalize"},{default:t(()=>[i(o(a.namaBulan),1)]),_:2},1024),s(e(_),{class:"border-r border-border"},{default:t(()=>[i(o(a.tahun),1)]),_:2},1024),s(e(_),{class:"text-end border-r border-border"},{default:t(()=>[i(o(a.actual),1)]),_:2},1024),s(e(_),{class:"text-end border-r border-border"},{default:t(()=>[i(o(a.forecast),1)]),_:2},1024),s(e(_),{class:"text-end border-r border-border"},{default:t(()=>[i(o(a.mad),1)]),_:2},1024),s(e(_),{class:"text-end border-r border-border"},{default:t(()=>[i(o(a.mse),1)]),_:2},1024),s(e(_),{class:"text-end"},{default:t(()=>[i(o(a.mape),1)]),_:2},1024)]),_:2},1024))),256)),s(e(k),null,{default:t(()=>[s(e(_),{colspan:"4",class:"border-r border-border"},{default:t(()=>[i("Rata - rata")]),_:1}),s(e(_),{class:"border-r border-border"},{default:t(()=>[Ie]),_:1}),s(e(_),{class:"text-end border-r border-border"},{default:t(()=>{var a,d,u;return[i(o((u=(d=(a=e(l))==null?void 0:a.hasil)==null?void 0:d.average)==null?void 0:u.mad),1)]}),_:1}),s(e(_),{class:"text-end border-r border-border"},{default:t(()=>{var a,d,u;return[i(o((u=(d=(a=e(l))==null?void 0:a.hasil)==null?void 0:d.average)==null?void 0:u.mse),1)]}),_:1}),s(e(_),{class:"text-end"},{default:t(()=>{var a,d,u;return[i(o((u=(d=(a=e(l))==null?void 0:a.hasil)==null?void 0:d.average)==null?void 0:u.mape),1)]}),_:1})]),_:1}),s(e(k),null,{default:t(()=>[s(e(_),{colspan:"4",class:"border-r border-border"},{default:t(()=>{var a,d,u,z,D,N;return[i(" Prediksi "+o((u=(d=(a=e(l))==null?void 0:a.hasil)==null?void 0:d.nextForecast)==null?void 0:u.namaBulan)+" "+o((N=(D=(z=e(l))==null?void 0:z.hasil)==null?void 0:D.nextForecast)==null?void 0:N.tahun)+" ? ",1)]}),_:1}),s(e(_),{class:"border-r border-border"},{default:t(()=>{var a,d,u;return[i(o((u=(d=(a=e(l))==null?void 0:a.hasil)==null?void 0:d.nextForecast)==null?void 0:u.forecast),1)]}),_:1}),s(e(_),{colspan:"3",class:"text-end"},{default:t(()=>{var a,d,u;return[i(o(((u=(d=(a=e(l))==null?void 0:a.hasil)==null?void 0:d.average)==null?void 0:u.mape)*100),1)]}),_:1})]),_:1})]),_:1})]),_:1})):$("",!0)])):$("",!0),x.value=="grafik"?(p(),h("div",Oe,[Ue,s(e(H),{class:"lg:col-span-4",data:w},null,8,["data"]),r("div",Ge,[r("span",null,"Prediksi "+o((j=(C=(P=e(l))==null?void 0:P.hasil)==null?void 0:C.nextForecast)==null?void 0:j.namaBulan)+" "+o((E=(B=(A=e(l))==null?void 0:A.hasil)==null?void 0:B.nextForecast)==null?void 0:E.tahun)+" Adalah",1),r("span",Le,o((T=(M=(S=e(l))==null?void 0:S.hasil)==null?void 0:M.nextForecast)==null?void 0:T.forecast),1)])])):$("",!0)])])}}};export{ea as default};
