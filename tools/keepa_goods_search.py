import keepa
import asyncio

key = '5ug2rp1e0pmbhm8a6mlrd8eu34rh14a49559jd4ts0032c74dsnt4rts5n0erjmr'
api = keepa.Keepa(key)
deal_parms = {"page": 0,
              "domainId": 1,
              "excludeCategories": [1064954, 11091801],
              "includeCategories": [16310101]}
deals = api.deals(deal_parms)

print(deals)
exit()


# ファイル書き込み
path_e = '/home/bitnami/stack/wordpress/wp-content/plugins/stock-screening/tools'
path_w = path_e + '/result/test.json'

s = 'New file'

with open(path_w, mode='w') as f:
	f.write(s)
	f.write('before')
	f.write('after')
	exit()