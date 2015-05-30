<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
	<xsl:call-template name="menu"/>
	<form id="grid_form_id">
		<table class="list">
			<tr>
            	<th class="th1">ID</th>
                <th class="th2">Event</th>
                <th class="th7">Gender</th>
                <th class="th3">Ranking</th>
                <th class="th4">Winner School</th>
                <th class="th5">Winner Name</th>
                <th class="th6">Points</th>
            </tr>
            <xsl:for-each select="data/grid/row">
            	<xsl:element name="tr">
                	<xsl:attribute name="id">
                    <xsl:value-of select="eventID" />
                    </xsl:attribute>
                    <td><xsl:value-of select="eventID" /></td>
                    <td><xsl:value-of select="eventName" /></td>
                    <td><xsl:value-of select="gender" /></td>
                    <td><xsl:value-of select="ranking" /></td>
                    <td><xsl:value-of select="winner" /></td>
                    <td><xsl:value-of select="winnerName" /></td>
                    <td><xsl:value-of select="points" /></td>
                   	<td><xsl:element name="a">
                    	<xsl:attribute name="href">#</xsl:attribute>
                        <xsl:attribute name="onclick">
                        	editId(<xsl:value-of select="eventID" />, 'edit')
                        </xsl:attribute>
                        Edit
                        </xsl:element>
                    </td>
                 </xsl:element>
             </xsl:for-each>
         </table>
      </form>
      <xsl:call-template name="menu" />
      </xsl:template>
      <xsl:template name="menu">
      	<xsl:for-each select="data/params">
        	<table>
            	<tr>
                	<td class="left">
                    	<xsl:value-of select="items_count" />Winners</td>
                        </tr>
                        </table>
                        </xsl:for-each>
                        </xsl:template>
</xsl:stylesheet>