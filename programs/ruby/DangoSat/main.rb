#!mruby
#V2.53
# AE-AQM1602XA-RN-GBW
ADD = 0x3E
Usb = Serial.new(0)
LoRa = Serial.new(1,115200)

while(Usb.available > 0)do	#USBのシリアルバッファクリア
  Usb.read
end
while(LoRa.available > 0)do  #LoRa側のシリアルバッファクリア
  LoRa.read
end

def rtc_init()
  tm1 = Rtc.getTime
  delay 1100
  tm2 = Rtc.getTime

  if(tm1[5] == tm2[5] || tm1[0] < 2010)then
      puts 'RTC Initialized'
      Rtc.init
      Rtc.setTime([2020,9,29,6,0,0])
  end
end

def zeroAdd(num)
  str = "00" + num.to_s
  str[str.length-2..str.length]
end

#puts "Input Date and time. Example:"
#     #0123456789012345678;
#puts "2020/09/15 05:24:00;"
#puts "-------------------"
def commandRead()
  readbuff = "#"
  command_get = 0
  cnt = 0
  loop do
    while(Usb.available() > 0) do #何か受信があったらreadbuffに蓄える
      a = Usb.read()
      readbuff += a
      Usb.print a
      if a.to_s == ";" then
        command_get = 1
        break
      end
      delay 20
      cnt = 0
    end #while

    if readbuff.length > 0 then
      cnt += 1
      if cnt > 500 then
        command_get = 1
        break
      end      
      delay 20
    end    

    if command_get==1 || readbuff=="" then
      break
    end
  end #loop

  if command_get==1 then
    command_get = 0
    if(readbuff[5]=="/" and readbuff[8]=="/" and readbuff[11]==" " and readbuff[14]==":" and readbuff[17]==":")then
      if(readbuff.length >= 19 )then
        year = readbuff[1,4].to_i
        mon	 = readbuff[6,2].to_i
        da	 = readbuff[9,2].to_i
        ho	 = readbuff[12,2].to_i
        min	 = readbuff[15,2].to_i
        sec	 = readbuff[18,2].to_i
        Rtc.deinit()
        #Rtc.init(-20)	# RTC補正：10 秒毎に 20/32768 秒遅らせる
        Rtc.init()      # v2.83以降：デフォルト値(-20)で補正を行う
        Rtc.setTime([year,mon,da,ho,min,sec])
      end
      year,mon,da,ho,min,sec = Rtc.getTime()
      puts ""
      puts year.to_s + "/" + zeroAdd(mon) + "/" + zeroAdd(da) + " " + zeroAdd(ho) + ":" + zeroAdd(min) + ":" + zeroAdd(sec)
      puts "RTC setteing is done."
    else
      puts ""
      puts "Illegal command:" + readbuff
      readbuff = ""
    end #if
  end
end

#####
#時計の表示
#####
def dispTime()
  year,mon,da,ho,min,sec = Rtc.getTime
  body = "'" + zeroAdd(year-2000) + "/" + zeroAdd(mon) + "/" + zeroAdd(da)
  if((sec % 2)==0)then
    c=" "
    led 0
  else
    c=":"
    led 1
  end
  body += " " + zeroAdd(ho) + c + zeroAdd(min) + " "

  if(Last_sec != sec) then
    Last_sec = sec
    puts body
  end
end
###
#LoRa通信の初期化
def initLoRa()
  c = "2\r\n"
  LoRa.write c, c.length
  delay 500
  c = "z\r\n"
  LoRa.write c, c.length
  delay 500
end

rtc_init
initLoRa    #LoRa通信の初期化

Last_sec = 0    #前の秒カウント値
lines = ""
loop do

  while(Usb.available() > 0) do
    a = Usb.read()
    if a.to_s == "#" then
      commandRead
    end
  end #while

  dispTime  #時計の表示 

  while(LoRa.available() > 0) do
    c = LoRa.read()
    for i in 0..(c.length - 1)
      lines = lines + c.bytes[i].chr
      if(c.bytes[i] == 0x0A)then
        #puts lines
        disp2 lines     #文字列表示
        if(lines[0] == "O" && lines[1] == "K")then
          #OKのときは何もしない
        elsif(lines[0] == "N" && lines[1] == "G")then
            #NGのときは何もしない
        else
          #返ってきた文字列を保存
          saveTxt lines
        end
        lines = ""
        GC.start
      end
    end
  end
  
  delay 10
end #loop
#System.exit()
